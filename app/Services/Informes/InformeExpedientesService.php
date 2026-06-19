<?php

declare(strict_types=1);

namespace App\Services\Informes;

use App\DTOs\Informes\InformeExpedientesFilaData;
use App\DTOs\Informes\InformeExpedientesFiltrosData;
use App\DTOs\Informes\InformeExpedientesGrupoData;
use App\DTOs\Informes\InformeExpedientesResultadoData;
use App\Enums\InformeExpedientesAgrupacion;
use App\Models\Expediente;
use DateTimeImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

final class InformeExpedientesService
{
    public function generar(InformeExpedientesFiltrosData $filtros): InformeExpedientesResultadoData
    {
        $query = $this->buildQuery($filtros);

        /** @var Collection<int, stdClass> $rows */
        $rows = $query->get();

        /** @var array<int, InformeExpedientesFilaData> $filas */
        $filas = $rows
            ->map(fn (stdClass $row): InformeExpedientesFilaData => $this->mapRowToDto($row, $filtros->agrupacion))
            ->all();

        $grupos = $this->groupRows($filas);
        $totales = $this->calculateTotals($grupos);

        return new InformeExpedientesResultadoData(
            filtros: $filtros,
            grupos: $grupos,
            totalExpedientes: $totales['total_expedientes'],
            totalImporteAprobado: $totales['total_importe_aprobado'],
            generadoEn: new DateTimeImmutable(),
        );
    }

    private function buildQuery(InformeExpedientesFiltrosData $filtros): Builder
    {
        $connectionName = (new Expediente())->getConnectionName();

        $query = DB::connection($connectionName)
            ->table('Expedientes as expedientes')
            ->select([
                'expedientes.expediente_id',
                'expedientes.nombre_obra',
                'expedientes.ao_ejecucion',
                'expedientes.cod_estado',
                'estados.estado as estado_nombre',
                'teams.name as team_nombre',
                DB::raw('COALESCE(importes.importe_aprobado, 0) as importe_aprobado'),
            ])
            ->leftJoin('TablaDeEstados as estados', 'estados.cod_estado', '=', 'expedientes.cod_estado')
            ->leftJoin('teams', 'teams.id', '=', 'expedientes.team_id')
            ->leftJoin('ImportesDeObras as importes', 'importes.expediente_id', '=', 'expedientes.expediente_id');

        if ($filtros->anioDesde !== null) {
            $query->where('expedientes.ao_ejecucion', '>=', $filtros->anioDesde);
        }

        if ($filtros->anioHasta !== null) {
            $query->where('expedientes.ao_ejecucion', '<=', $filtros->anioHasta);
        }

        if ($filtros->codEstado !== null) {
            $query->where('expedientes.cod_estado', '=', $filtros->codEstado);
        }

        if ($filtros->teamId !== null) {
            $query->where('expedientes.team_id', '=', $filtros->teamId);
        }

        return $this->applySorting($query, $filtros->agrupacion);
    }

    private function applySorting(Builder $query, InformeExpedientesAgrupacion $agrupacion): Builder
    {
        return match ($agrupacion) {
            InformeExpedientesAgrupacion::ESTADO => $query
                ->orderBy('estados.estado')
                ->orderByDesc('expedientes.ao_ejecucion')
                ->orderBy('expedientes.expediente_id'),
            InformeExpedientesAgrupacion::MUNICIPIO => $query
                ->orderBy('teams.name')
                ->orderByDesc('expedientes.ao_ejecucion')
                ->orderBy('expedientes.expediente_id'),
            InformeExpedientesAgrupacion::ANIO => $query
                ->orderByDesc('expedientes.ao_ejecucion')
                ->orderBy('expedientes.expediente_id'),
        };
    }

    private function mapRowToDto(stdClass $row, InformeExpedientesAgrupacion $agrupacion): InformeExpedientesFilaData
    {
        $expedienteId = trim($this->toString($row->expediente_id ?? ''));
        $nombreObra = trim($this->toString($row->nombre_obra ?? ''));
        $estado = trim($this->toString($row->estado_nombre ?? 'Sin estado'));
        $municipio = trim($this->toString($row->team_nombre ?? 'Sin municipio'));
        $anioEjecucion = $this->toInt($row->ao_ejecucion ?? 0);
        $importeAprobado = $this->toFloat($row->importe_aprobado ?? 0.0);

        $claveGrupo = $this->resolveGroupKey($agrupacion, $estado, $municipio, $anioEjecucion);
        $etiquetaGrupo = $this->resolveGroupLabel($agrupacion, $estado, $municipio, $anioEjecucion);

        return new InformeExpedientesFilaData(
            claveGrupo: $claveGrupo,
            etiquetaGrupo: $etiquetaGrupo,
            expedienteId: $expedienteId,
            nombreObra: $nombreObra,
            estado: $estado,
            municipio: $municipio,
            anioEjecucion: $anioEjecucion,
            importeAprobado: $importeAprobado,
        );
    }

    private function resolveGroupKey(
        InformeExpedientesAgrupacion $agrupacion,
        string $estado,
        string $municipio,
        int $anioEjecucion,
    ): string {
        return match ($agrupacion) {
            InformeExpedientesAgrupacion::ESTADO => $estado,
            InformeExpedientesAgrupacion::MUNICIPIO => $municipio,
            InformeExpedientesAgrupacion::ANIO => (string) $anioEjecucion,
        };
    }

    private function resolveGroupLabel(
        InformeExpedientesAgrupacion $agrupacion,
        string $estado,
        string $municipio,
        int $anioEjecucion,
    ): string {
        return match ($agrupacion) {
            InformeExpedientesAgrupacion::ESTADO => $estado,
            InformeExpedientesAgrupacion::MUNICIPIO => $municipio,
            InformeExpedientesAgrupacion::ANIO => 'Año ' . $anioEjecucion,
        };
    }

    /**
     * @param array<int, InformeExpedientesFilaData> $rows
     * @return array<int, InformeExpedientesGrupoData>
     */
    private function groupRows(array $rows): array
    {
        /** @var array<string, array{etiqueta: string, filas: array<int, InformeExpedientesFilaData>}> $grouped */
        $grouped = [];

        foreach ($rows as $row) {
            if (! array_key_exists($row->claveGrupo, $grouped)) {
                $grouped[$row->claveGrupo] = [
                    'etiqueta' => $row->etiquetaGrupo,
                    'filas' => [],
                ];
            }

            $grouped[$row->claveGrupo]['filas'][] = $row;
        }

        /** @var array<int, InformeExpedientesGrupoData> $groups */
        $groups = [];

        foreach ($grouped as $key => $group) {
            $groups[] = new InformeExpedientesGrupoData(
                clave: $key,
                etiqueta: $group['etiqueta'],
                filas: $group['filas'],
            );
        }

        return $groups;
    }

    /**
     * @param array<int, InformeExpedientesGrupoData> $grupos
     * @return array{total_expedientes: int, total_importe_aprobado: float}
     */
    private function calculateTotals(array $grupos): array
    {
        $totalExpedientes = 0;
        $totalImporteAprobado = 0.0;

        foreach ($grupos as $grupo) {
            $totalExpedientes += $grupo->totalExpedientes();
            $totalImporteAprobado += $grupo->totalImporteAprobado();
        }

        return [
            'total_expedientes' => $totalExpedientes,
            'total_importe_aprobado' => $totalImporteAprobado,
        ];
    }

    private function toString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value) || is_bool($value)) {
            return (string) $value;
        }

        return '';
    }

    private function toInt(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return 0;
    }

    private function toFloat(mixed $value): float
    {
        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (float) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return 0.0;
    }

}
