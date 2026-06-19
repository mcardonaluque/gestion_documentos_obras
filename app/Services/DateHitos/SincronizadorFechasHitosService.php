<?php

declare(strict_types=1);

namespace App\Services\DateHitos;

use App\Models\ExpedienteFechaHito;
use App\Models\FechaHitoCatalogo;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class SincronizadorFechasHitosService
{
    public function sincronizarCatalogoDesdeConfig(): int
    {
        /** @var array<int, array<string, mixed>> $catalogo */
        $catalogo = config('date_rules.hitos_catalogo', []);

        $codigos = [];
        $afectados = 0;

        foreach ($catalogo as $indice => $hito) {
            $codigo = isset($hito['codigo_hito']) ? trim((string) $hito['codigo_hito']) : '';

            if ($codigo === '') {
                continue;
            }

            $codigos[] = $codigo;

            $modelo = FechaHitoCatalogo::query()->updateOrCreate(
                ['codigo_hito' => $codigo],
                [
                    'descripcion' => (string) ($hito['descripcion'] ?? ''),
                    'tabla_origen' => (string) ($hito['tabla_origen'] ?? ''),
                    'campo_origen' => (string) ($hito['campo_origen'] ?? ''),
                    'fase' => isset($hito['fase']) ? (string) $hito['fase'] : null,
                    'obligatorio' => (bool) ($hito['obligatorio'] ?? false),
                    'repetible' => (bool) ($hito['repetible'] ?? false),
                    'activa' => true,
                    'orden' => $indice + 1,
                ],
            );

            if ($modelo->wasRecentlyCreated || $modelo->wasChanged()) {
                $afectados++;
            }
        }

        if ($codigos !== []) {
            FechaHitoCatalogo::query()
                ->whereNotIn('codigo_hito', $codigos)
                ->where('activa', true)
                ->update(['activa' => false]);
        }

        return $afectados;
    }

    public function sincronizarTodo(bool $limpiarAntes = false): int
    {
        if ($limpiarAntes) {
            ExpedienteFechaHito::query()->delete();
        }

        return $this->sincronizarPorMapa();
    }

    public function sincronizarExpediente(string $expedienteId, bool $limpiarAntes = false): int
    {
        if ($limpiarAntes) {
            ExpedienteFechaHito::query()->where('expediente_id', $expedienteId)->delete();
        }

        return $this->sincronizarPorMapa($expedienteId);
    }

    private function sincronizarPorMapa(?string $expedienteId = null): int
    {
        /** @var array<int, array<string, mixed>> $catalogo */
        $catalogo = config('date_rules.hitos_catalogo', []);

        $contador = 0;

        foreach ($this->agruparPorTabla($catalogo) as $tabla => $hitosTabla) {
            $contador += $this->sincronizarTabla((string) $tabla, $hitosTabla, $expedienteId);
        }

        return $contador;
    }

    /**
     * @param array<int, array<string, mixed>> $catalogo
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function agruparPorTabla(array $catalogo): array
    {
        /** @var array<string, array<int, array<string, mixed>>> $agrupado */
        $agrupado = [];

        foreach ($catalogo as $hito) {
            $tabla = isset($hito['tabla_origen']) ? (string) $hito['tabla_origen'] : '';

            if ($tabla === '') {
                continue;
            }

            $agrupado[$tabla] ??= [];
            $agrupado[$tabla][] = $hito;
        }

        return $agrupado;
    }

    /**
     * @param array<int, array<string, mixed>> $hitosTabla
     */
    private function sincronizarTabla(string $tabla, array $hitosTabla, ?string $expedienteId): int
    {
        $columnasDisponibles = $this->obtenerColumnasDisponibles($tabla);

        if ($columnasDisponibles === []) {
            return 0;
        }

        if (! in_array('expediente_id', $columnasDisponibles, true)) {
            return 0;
        }

        $campos = ['expediente_id'];

        if (in_array('team_id', $columnasDisponibles, true)) {
            $campos[] = 'team_id';
        }

        foreach ($hitosTabla as $hito) {
            $campo = isset($hito['campo_origen']) ? (string) $hito['campo_origen'] : '';

            if ($campo !== '' && in_array($campo, $columnasDisponibles, true) && ! in_array($campo, $campos, true)) {
                $campos[] = $campo;
            }
        }

        $query = DB::connection('Obras')->table($tabla)->select($campos)->whereNotNull('expediente_id');

        if ($expedienteId !== null) {
            $query->where('expediente_id', $expedienteId);
        }

        /** @var Collection<int, object> $filas */
        $filas = $query->get();

        $contador = 0;

        foreach ($filas as $fila) {
            $expediente = $this->toString($fila->expediente_id ?? null);

            if ($expediente === null || $expediente === '') {
                continue;
            }

            $teamId = $this->toNullableInt($fila->team_id ?? null);

            foreach ($hitosTabla as $hito) {
                $contador += $this->guardarHito($expediente, $tabla, $hito, $fila, $teamId) ? 1 : 0;
            }
        }

        return $contador;
    }

    /**
     * @return array<int, string>
     */
    private function obtenerColumnasDisponibles(string $tabla): array
    {
        $schema = 'dbo';
        $tableName = $tabla;

        if (str_contains($tabla, '.')) {
            [$schema, $tableName] = array_pad(explode('.', $tabla, 2), 2, '');
            $schema = trim($schema) !== '' ? trim($schema) : 'dbo';
            $tableName = trim($tableName);
        }

        if ($tableName === '') {
            return [];
        }

        /** @var Collection<int, object> $rows */
        $rows = DB::connection('Obras')
            ->table('INFORMATION_SCHEMA.COLUMNS')
            ->select('COLUMN_NAME')
            ->where('TABLE_SCHEMA', $schema)
            ->where('TABLE_NAME', $tableName)
            ->get();

        /** @var array<int, string> $columnas */
        $columnas = $rows
            ->map(static fn (object $row): string => (string) ($row->COLUMN_NAME ?? ''))
            ->filter(static fn (string $name): bool => $name !== '')
            ->values()
            ->all();

        return $columnas;
    }

    /**
     * @param array<string, mixed> $hito
     */
    private function guardarHito(string $expedienteId, string $tabla, array $hito, object $fila, ?int $teamId): bool
    {
        $codigoHito = isset($hito['codigo_hito']) ? (string) $hito['codigo_hito'] : '';
        $campoOrigen = isset($hito['campo_origen']) ? (string) $hito['campo_origen'] : '';

        if ($codigoHito === '' || $campoOrigen === '') {
            return false;
        }

        $valor = $fila->{$campoOrigen} ?? null;

        if ($valor === null || $valor === '') {
            return false;
        }

        $fecha = $this->toDateTime($valor);

        if ($fecha === null) {
            return false;
        }

        ExpedienteFechaHito::query()->updateOrCreate(
            [
                'expediente_id' => $expedienteId,
                'codigo_hito' => $codigoHito,
                'source_record_id' => null,
            ],
            [
                'fecha' => $fecha,
                'tabla_origen' => $tabla,
                'campo_origen' => $campoOrigen,
                'team_id' => $teamId,
            ],
        );

        return true;
    }

    private function toDateTime(mixed $value): ?Carbon
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (! is_scalar($value)) {
            return null;
        }

        $raw = trim((string) $value);

        if ($raw === '') {
            return null;
        }

        try {
            return Carbon::parse($raw);
        } catch (\Throwable) {
            return null;
        }
    }

    private function toString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! is_scalar($value)) {
            return null;
        }

        return (string) $value;
    }

    private function toNullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }
}
