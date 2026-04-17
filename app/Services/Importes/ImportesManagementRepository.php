<?php

declare(strict_types=1);

namespace App\Services\Importes;

use App\DTOs\ImportesOrganismoRowData;
use App\Models\Expediente;
use App\Models\ImportesDeObras;
use App\Models\ImportesPorOrganismo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ImportesManagementRepository
{
    /**
     * @return array{master: array<string, float>, rows: array<int, array<string, float|string>>, stage_hint: string}
     */
    public function load(string $expedienteId): array
    {
        $masterModel = ImportesDeObras::query()->where('expediente_id', $expedienteId)->first();

        /** @var Collection<int, ImportesPorOrganismo> $rowsModels */
        $rowsModels = ImportesPorOrganismo::query()
            ->where('expediente_id', $expedienteId)
            ->orderBy('organismo')
            ->get();

        $rows = [];

        foreach ($rowsModels as $rowModel) {
            $rows[] = $this->toRowData($rowModel)->toArray();
        }

        if ($rows === []) {
            $rows = [
                (new ImportesOrganismoRowData('DIP', 50.0, 0.0, 50.0, 0.0, 50.0, 0.0, 0.0, 0.0, 50.0, 0.0))->toArray(),
                (new ImportesOrganismoRowData('AYT', 50.0, 0.0, 50.0, 0.0, 50.0, 0.0, 0.0, 0.0, 50.0, 0.0))->toArray(),
            ];
        }

        return [
            'master' => [
                'approved_total' => $this->getValue($masterModel, ['importe_aprobado']),
                'contract_total' => $this->getValue($masterModel, ['importe_a_contratar', 'Importe_a_contratar']),
                'awarded_total' => $this->getValue($masterModel, ['importe_adjudicacion']),
                'drop_total' => $this->getValue($masterModel, ['importe_baja_contratacion', 'importe_baja_contratación']),
                'executed_total' => $this->getValue($masterModel, ['importe_ejecutado']),
            ],
            'rows' => $rows,
            'stage_hint' => $this->resolveStageHint($expedienteId),
        ];
    }

    /**
     * @param array<string, float|int|string|null> $master
     * @param array<int, array<string, float|string>> $rows
     */
    public function save(string $expedienteId, array $master, array $rows): void
    {
        $masterModel = ImportesDeObras::query()->firstOrNew([
            'expediente_id' => $expedienteId,
        ]);

        $this->assignFirstExisting($masterModel, ['importe_aprobado'], $master['approved_total'] ?? 0.0);
        $this->assignFirstExisting($masterModel, ['importe_a_contratar', 'Importe_a_contratar'], $master['contract_total'] ?? 0.0);
        $this->assignFirstExisting($masterModel, ['importe_adjudicacion'], $master['awarded_total'] ?? 0.0);
        $this->assignFirstExisting($masterModel, ['importe_baja_contratacion', 'importe_baja_contratación'], $master['drop_total'] ?? 0.0);
        $this->assignFirstExisting($masterModel, ['importe_ejecutado'], $master['executed_total'] ?? 0.0);

        $masterModel->save();

        foreach ($rows as $row) {
            $organismo = strtoupper(trim((string) ($row['organismo'] ?? '')));

            if ($organismo === '') {
                continue;
            }

            $rowModel = ImportesPorOrganismo::query()->firstOrNew([
                'expediente_id' => $expedienteId,
                'organismo' => $organismo,
            ]);

            $rowModel->organismo = $organismo;

            $awardedAmount = (float) ($row['awarded_amount'] ?? 0.0);
            $dropPercent = $awardedAmount > 0.0 ? (float) ($row['drop_percent'] ?? 0.0) : 0.0;
            $dropAmount = $awardedAmount > 0.0 ? (float) ($row['drop_amount'] ?? 0.0) : 0.0;

            $this->assignFirstExisting($rowModel, ['Porc_imp_aprobado', 'porc_imp_aprobado'], $row['approved_percent'] ?? 0.0);
            $this->assignFirstExisting($rowModel, ['importe_aprobado'], $row['approved_amount'] ?? 0.0);
            $this->assignFirstExisting($rowModel, ['Porc_imp_contratar', 'Porc_importe_contratar', 'porc_imp_contratar'], $row['contract_percent'] ?? 0.0);
            $this->assignFirstExisting($rowModel, ['Importe_a_contratar', 'importe_a_contratar'], $row['contract_amount'] ?? 0.0);
            $this->assignFirstExisting($rowModel, ['Porc_imp_adjudicado', 'porc_imp_adjudicado'], $row['awarded_percent'] ?? 0.0);
            $this->assignFirstExisting($rowModel, ['importe_adjudicacion'], $awardedAmount);
            $this->assignFirstExisting($rowModel, ['Porc_imp_baja', 'Porc_imp_baj', 'porc_imp_baja'], $dropPercent);
            $this->assignFirstExisting($rowModel, ['importe_baja_contratacion', 'importe_baja_contratación'], $dropAmount);
            $this->assignFirstExisting($rowModel, ['importe_ejecutado'], $row['executed_amount'] ?? 0.0);
            $this->assignFirstExisting($rowModel, ['Porc_imp_ejecutado', 'porc_imp_ejecutado'], $row['executed_percent'] ?? 0.0);

            $rowModel->save();
        }
    }

    private function toRowData(ImportesPorOrganismo $row): ImportesOrganismoRowData
    {
        return new ImportesOrganismoRowData(
            organismo: strtoupper((string) ($row->organismo ?? 'ORG')),
            approvedPercent: $this->getValue($row, ['Porc_imp_aprobado', 'porc_imp_aprobado']),
            approvedAmount: $this->getValue($row, ['importe_aprobado']),
            contractPercent: $this->getValue($row, ['Porc_imp_contratar', 'Porc_importe_contratar', 'porc_imp_contratar']),
            contractAmount: $this->getValue($row, ['Importe_a_contratar', 'importe_a_contratar']),
            awardedPercent: $this->getValue($row, ['Porc_imp_adjudicado', 'porc_imp_adjudicado']),
            awardedAmount: $this->getValue($row, ['importe_adjudicacion']),
            dropPercent: $this->getValue($row, ['Porc_imp_baja', 'Porc_imp_baj', 'porc_imp_baja']),
            dropAmount: $this->getValue($row, ['importe_baja_contratacion', 'importe_baja_contratación']),
            executedPercent: $this->getValue($row, ['Porc_imp_ejecutado', 'porc_imp_ejecutado']),
            executedAmount: $this->getValue($row, ['importe_ejecutado']),
        );
    }

    /**
     * @param array<int, string> $aliases
     */
    private function getValue(?Model $model, array $aliases): float
    {
        if ($model === null) {
            return 0.0;
        }

        foreach ($aliases as $key) {
            $value = $model->getAttribute($key);
            if ($value !== null && $value !== '') {
                return (float) $value;
            }
        }

        return 0.0;
    }

    /**
     * @param array<int, string> $aliases
     */
    private function assignFirstExisting(Model $model, array $aliases, mixed $value): void
    {
        $attributes = $model->getAttributes();

        foreach ($aliases as $key) {
            if (array_key_exists($key, $attributes)) {
                $model->setAttribute($key, $value);

                return;
            }
        }

        $model->setAttribute($aliases[0], $value);
    }

    private function resolveStageHint(string $expedienteId): string
    {
        $expediente = Expediente::query()->with('estados')->where('expediente_id', $expedienteId)->first();

        if ($expediente === null) {
            return 'inicio';
        }

        $stateRaw = strtolower(trim((string) ($expediente->cod_estado ?? '')));
        $stateName = strtolower(trim((string) ($expediente->estados?->descripcion_estado ?? $expediente->estados?->nombre_estado ?? '')));
        $state = $stateRaw . ' ' . $stateName;

        if (str_contains($state, 'ejec')) {
            return 'ejecucion';
        }

        if (str_contains($state, 'contrat')) {
            return 'contratacion';
        }

        if (str_contains($state, 'ces')) {
            return 'cesion';
        }

        return 'inicio';
    }
}
