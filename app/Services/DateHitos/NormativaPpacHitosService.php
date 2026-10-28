<?php

declare(strict_types=1);

namespace App\Services\DateHitos;

use App\Models\Expediente;
use App\Models\NormativaPpac;
use App\Models\PlazoObraActivo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Calcula los hitos de fechas que la normativa PPAC fija para un expediente.
 */
final class NormativaPpacHitosService
{
    /**
     * Normativa aplicable al expediente (la de su plan o la general del año).
     */
    public function normativaDe(Expediente $expediente): ?NormativaPpac
    {
        return NormativaPpac::resolveFor($expediente->ao_ejecucion, $expediente->codigo_plan);
    }

    /**
     * @return Collection<int, array{codigo_hito: string, descripcion: string, fase: string, campo: string, fecha: Carbon, cumplido_con: list<string>}>
     */
    public function hitosDe(Expediente $expediente, ?NormativaPpac $normativa = null): Collection
    {
        $normativa ??= $this->normativaDe($expediente);

        if ($normativa === null) {
            return collect();
        }

        $redactaDiputacion = (bool) ($expediente->proyecto?->SubvencionEconRedaccion ?? false);
        $plazos = $this->plazosVigentes($expediente);
        $hitos = collect();

        foreach ((array) config('date_rules.normativa_hitos', []) as $definicion) {
            $campo = $this->campoPara($definicion['campo'] ?? null, $redactaDiputacion);
            $fecha = $campo !== null ? $normativa->getAttribute($campo) : null;

            if ($fecha === null || $fecha === '') {
                continue;
            }

            $fecha = Carbon::parse($fecha);
            $plazoFase = $definicion['plazo_fase'] ?? null;

            // Una prórroga aprobada desplaza el hito más allá de la fecha de la normativa.
            if ($plazoFase !== null && isset($plazos[$plazoFase])) {
                $fechaPlazo = Carbon::parse($plazos[$plazoFase]);

                if ($fechaPlazo->greaterThan($fecha)) {
                    $fecha = $fechaPlazo;
                }
            }

            $hitos->push([
                'codigo_hito' => (string) $definicion['codigo_hito'],
                'descripcion' => (string) $definicion['descripcion'],
                'fase' => (string) ($definicion['fase'] ?? ''),
                'campo' => $campo,
                'fecha' => $fecha->copy()->startOfDay(),
                'cumplido_con' => array_values((array) ($definicion['cumplido_con'] ?? [])),
            ]);
        }

        return $hitos;
    }

    /**
     * Etapa de aviso de un hito: 'preaviso', 'vencido' o null si no toca avisar.
     */
    public static function etapa(Carbon $fecha, Carbon $hoy, int $avisoDias, int $maxDiasVencido): ?string
    {
        $dias = (int) $hoy->copy()->startOfDay()->diffInDays($fecha->copy()->startOfDay(), false);

        if ($dias < 0) {
            return -$dias <= $maxDiasVencido ? 'vencido' : null;
        }

        return $dias <= $avisoDias ? 'preaviso' : null;
    }

    /**
     * @param  string|array<string, string>|null  $campo
     */
    private function campoPara(string|array|null $campo, bool $redactaDiputacion): ?string
    {
        if (is_array($campo)) {
            return $campo[$redactaDiputacion ? 'diputacion' : 'ayuntamiento'] ?? null;
        }

        return $campo;
    }

    /**
     * @return array<string, mixed>
     */
    private function plazosVigentes(Expediente $expediente): array
    {
        return PlazoObraActivo::query()
            ->where('expediente_id', $expediente->expediente_id)
            ->where('activo', true)
            ->orderBy('id')
            ->pluck('fecha_fin', 'fase')
            ->all();
    }
}
