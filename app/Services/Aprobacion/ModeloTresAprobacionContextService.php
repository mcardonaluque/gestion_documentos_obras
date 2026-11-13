<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\DatosDeInicioDeObras;
use App\Models\ImportesPorOrganismo;
use App\Models\TablaDeMunicipio;
use Illuminate\Validation\ValidationException;

final class ModeloTresAprobacionContextService
{
    /** @param array<string, mixed> $data @return array<string, mixed> */
    public function enrich(array $data): array
    {
        $context = $this->contextForExpediente(trim((string) ($data['expediente_id'] ?? '')));
        $modalidad = (string) ($data['modalidad_presupuesto'] ?? '');
        if (! in_array($modalidad, ['vigente', 'anticipado'], true)) {
            throw ValidationException::withMessages(['modalidad_presupuesto' => 'Seleccione presupuesto en vigor o crédito anticipado.']);
        }

        $secretario = trim((string) ($data['secretario_nombre'] ?? ''));

        return [...$context, ...$data, 'team_id' => $context['team_id'], 'entidad_local' => $context['entidad_local'], 'secretario_nombre' => $secretario !== '' ? $secretario : $context['secretario_nombre'], 'anualidad' => now()->year + ($modalidad === 'anticipado' ? 1 : 0)];
    }

    /** @return array<string, int|string|float|null> */
    public function contextForExpediente(string $expedienteId): array
    {
        $obra = DatosDeInicioDeObras::query()->where('expediente_id', $expedienteId)->first();
        if (! $obra instanceof DatosDeInicioDeObras) {
            throw ValidationException::withMessages(['expediente_id' => 'El expediente seleccionado no tiene datos de inicio de obra.']);
        }
        $municipio = TablaDeMunicipio::query()->where('codigo_municipio', $obra->municipio)->first();
        $importe = ImportesPorOrganismo::query()->where('expediente_id', $expedienteId)->whereRaw("RTRIM(organismo) = 'AYT'")->sum('importe_aprobado');
        $nombre = implode(' ', array_filter(array_map(fn (string $campo): string => trim((string) $obra->getAttribute($campo)), ['nombre_obra1', 'nombre_obra2', 'nombre_obra3'])));

        return ['expediente_id' => $expedienteId, 'codigo_plan' => trim((string) $obra->Codigo_Plan), 'numero_obra' => (int) $obra->numero_obra, 'subreferencia' => (int) $obra->subreferencia, 'ao_ejecucion' => (int) $obra->ao_ejecucion, 'nombre_obra' => $nombre, 'codigo_municipio' => (int) $obra->municipio, 'entidad_local' => trim((string) $municipio?->nombre_municipio), 'secretario_nombre' => trim((string) $municipio?->Secretario), 'importe_aprobado_ayuntamiento' => (float) $importe, 'partida_presupuestaria' => trim((string) $obra->PartidaPresupuesto), 'team_id' => (int) $obra->team_id];
    }
}
