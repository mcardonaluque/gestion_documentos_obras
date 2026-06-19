<?php

declare(strict_types=1);

namespace App\Services\DateHitos;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class ConsultaExpedienteFechaHitosService
{
    /**
     * @param array<string, mixed> $filtros
    * @return array<string, mixed>
     */
    public function buscar(array $filtros): array
    {
        $query = DB::connection('Obras')
            ->query()
            ->from('expediente_fecha_hitos', 'hitos')
            ->leftJoin('fecha_hitos_catalogo as catalogo', 'catalogo.codigo_hito', '=', 'hitos.codigo_hito')
            ->leftJoin('teams', 'teams.id', '=', 'hitos.team_id')
            ->select([
                'hitos.expediente_id',
                'hitos.codigo_hito',
                'catalogo.descripcion as descripcion_hito',
                'hitos.fecha',
                'hitos.tabla_origen',
                'hitos.campo_origen',
                'teams.name as team_nombre',
                'hitos.updated_at',
            ]);

        $this->aplicarFiltros($query, $filtros);

        $limite = $this->resolverLimite($filtros['limite'] ?? null);
        $total = (clone $query)->count();

        $rows = $query
            ->orderByDesc('hitos.updated_at')
            ->orderByDesc('hitos.fecha')
            ->limit($limite)
            ->get();

        $filas = $rows->map(static function (object $row): array {
            return [
                'expediente_id' => is_scalar($row->expediente_id ?? null) ? (string) $row->expediente_id : '',
                'codigo_hito' => is_scalar($row->codigo_hito ?? null) ? (string) $row->codigo_hito : '',
                'descripcion_hito' => is_scalar($row->descripcion_hito ?? null) ? (string) $row->descripcion_hito : null,
                'fecha' => $row->fecha instanceof \DateTimeInterface
                    ? $row->fecha->format('d/m/Y H:i')
                    : (is_scalar($row->fecha ?? null) ? (string) $row->fecha : ''),
                'tabla_origen' => is_scalar($row->tabla_origen ?? null) ? (string) $row->tabla_origen : '',
                'campo_origen' => is_scalar($row->campo_origen ?? null) ? (string) $row->campo_origen : '',
                'team_nombre' => is_scalar($row->team_nombre ?? null) ? (string) $row->team_nombre : null,
                'updated_at' => $row->updated_at instanceof \DateTimeInterface
                    ? $row->updated_at->format('d/m/Y H:i')
                    : (is_scalar($row->updated_at ?? null) ? (string) $row->updated_at : ''),
            ];
        })->all();

        return [
            'filas' => $filas,
            'total' => $total,
            'generado_en' => now()->format('d/m/Y H:i'),
            'tiene_resultados' => $total > 0,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function opcionesHitos(): array
    {
        $options = DB::connection('Obras')
            ->query()
            ->from('expediente_fecha_hitos', 'hitos')
            ->leftJoin('fecha_hitos_catalogo as catalogo', 'catalogo.codigo_hito', '=', 'hitos.codigo_hito')
            ->selectRaw("hitos.codigo_hito, COALESCE(catalogo.descripcion, hitos.codigo_hito) as etiqueta")
            ->distinct()
            ->orderBy('hitos.codigo_hito')
            ->pluck('etiqueta', 'hitos.codigo_hito')
            ->toArray();

        return $options;
    }

    /**
     * @return array<string, string>
     */
    public function opcionesTablas(): array
    {
        $options = DB::connection('Obras')
            ->query()
            ->from('expediente_fecha_hitos')
            ->orderBy('tabla_origen')
            ->distinct()
            ->pluck('tabla_origen', 'tabla_origen')
            ->toArray();

        return $options;
    }

    /**
     * @param array<string, mixed> $filtros
     */
    private function aplicarFiltros(Builder $query, array $filtros): void
    {
        $expedienteId = $this->stringOrNull($filtros['expediente_id'] ?? null);
        $codigoHito = $this->stringOrNull($filtros['codigo_hito'] ?? null);
        $tablaOrigen = $this->stringOrNull($filtros['tabla_origen'] ?? null);
        $fechaDesde = $this->stringOrNull($filtros['fecha_desde'] ?? null);
        $fechaHasta = $this->stringOrNull($filtros['fecha_hasta'] ?? null);

        if ($expedienteId !== null) {
            $query->where('hitos.expediente_id', 'like', '%' . $expedienteId . '%');
        }

        if ($codigoHito !== null) {
            $query->where('hitos.codigo_hito', $codigoHito);
        }

        if ($tablaOrigen !== null) {
            $query->where('hitos.tabla_origen', $tablaOrigen);
        }

        if ($fechaDesde !== null) {
            $query->whereDate('hitos.fecha', '>=', $fechaDesde);
        }

        if ($fechaHasta !== null) {
            $query->whereDate('hitos.fecha', '<=', $fechaHasta);
        }
    }

    private function resolverLimite(mixed $value): int
    {
        if (is_numeric($value)) {
            return max(25, min(500, (int) $value));
        }

        return 100;
    }

    private function stringOrNull(mixed $value): ?string
    {
        if (! is_scalar($value)) {
            return null;
        }

        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }
}
