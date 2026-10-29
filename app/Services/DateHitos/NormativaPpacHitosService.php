<?php

declare(strict_types=1);

namespace App\Services\DateHitos;

use App\Models\DateValidationRule;
use App\Models\Expediente;
use App\Models\NormativaPpac;
use App\Models\PlazoObraActivo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Resuelve las fechas limite de la normativa PPAC para un expediente y las
 * reglas de date_validation_rules que las comparan (tabla2 = NormativaPPAC).
 */
final class NormativaPpacHitosService
{
    public const TABLA = 'NormativaPPAC';

    /** Fase de PlazosObraActivos cuya fecha (con prorrogas) sustituye a la de la normativa. */
    private const PLAZO_FASE_POR_CAMPO = [
        'fecha_limite_presentacion_proyectod' => 'proyecto_memoria',
        'fecha_limite_presentacion_proyectoa' => 'proyecto_memoria',
        'fecha_limite_presentacion_documentaciond' => 'documentacion',
        'fecha_limite_presentacion_documentaciona' => 'documentacion',
        'fecha_limite_terminacion_plan' => 'ejecucion',
        'fecha_limite_justificacion' => 'justificacion',
    ];

    /** @var Collection<int, DateValidationRule>|null */
    private ?Collection $reglas = null;

    public static function esNormativa(?string $tabla): bool
    {
        return $tabla !== null && strcasecmp(trim($tabla), self::TABLA) === 0;
    }

    /**
     * Normativa aplicable al expediente (la de su plan o la general del año).
     */
    public function normativaDe(Expediente $expediente): ?NormativaPpac
    {
        return NormativaPpac::resolveFor($expediente->ao_ejecucion, $expediente->codigo_plan);
    }

    /**
     * Fecha limite del campo indicado para el expediente. Los campos que
     * terminan en D/A (Diputacion/Ayuntamiento) se eligen segun quien redacta
     * el proyecto, y una prorroga posterior sustituye a la fecha de normativa.
     */
    public function fechaLimite(Expediente $expediente, string $campo, ?NormativaPpac $normativa = null): ?Carbon
    {
        $normativa ??= $this->normativaDe($expediente);

        if ($normativa === null) {
            return null;
        }

        $campo = $this->campoSegunRedaccion($normativa, $expediente, $campo);
        $valor = $normativa->getAttribute($campo);

        if ($valor === null || $valor === '') {
            return null;
        }

        $fecha = Carbon::parse($valor)->startOfDay();
        $fase = self::PLAZO_FASE_POR_CAMPO[strtolower($campo)] ?? null;

        if ($fase !== null) {
            $plazo = PlazoObraActivo::query()
                ->where('expediente_id', $expediente->expediente_id)
                ->where('fase', $fase)
                ->where('activo', true)
                ->orderByDesc('fecha_fin')
                ->value('fecha_fin');

            if ($plazo !== null && Carbon::parse($plazo)->startOfDay()->greaterThan($fecha)) {
                $fecha = Carbon::parse($plazo)->startOfDay();
            }
        }

        return $fecha;
    }

    /**
     * Reglas activas que comparan una fecha del expediente con la normativa.
     *
     * @return Collection<int, DateValidationRule>
     */
    public function reglas(): Collection
    {
        return $this->reglas ??= DateValidationRule::query()
            ->active()
            ->where('tabla2', self::TABLA)
            ->orderBy('id')
            ->get();
    }

    /**
     * @return Collection<int, array{regla: DateValidationRule, fecha_limite: Carbon, resuelto: bool}>
     */
    public function hitosDe(Expediente $expediente): Collection
    {
        $normativa = $this->normativaDe($expediente);

        if ($normativa === null) {
            return collect();
        }

        $hitos = collect();

        foreach ($this->reglas() as $regla) {
            $limite = $this->fechaLimite($expediente, (string) $regla->campo2, $normativa);

            if ($limite === null) {
                continue;
            }

            $real = $this->fechaReal($expediente, $regla);

            // Con fecha real ya hay cumplimiento (o incumplimiento ya validado al guardar): no se avisa.
            $hitos->push(['regla' => $regla, 'fecha_limite' => $limite, 'resuelto' => $real !== null]);
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

    private function fechaReal(Expediente $expediente, DateValidationRule $regla): ?Carbon
    {
        $valor = DB::connection($regla->connection_name ?: 'Obras')
            ->table($regla->tabla1)
            ->where('expediente_id', $expediente->expediente_id)
            ->whereNotNull($regla->campo1)
            ->value($regla->campo1);

        return $valor === null || $valor === '' ? null : Carbon::parse($valor);
    }

    private function campoSegunRedaccion(NormativaPpac $normativa, Expediente $expediente, string $campo): string
    {
        if (! preg_match('/^(.*)[DA]$/', $campo, $coincidencia)) {
            return $campo;
        }

        $redactaDiputacion = (bool) ($expediente->proyecto?->SubvencionEconRedaccion ?? false);
        $candidato = $coincidencia[1] . ($redactaDiputacion ? 'D' : 'A');

        return array_key_exists($candidato, $normativa->getAttributes()) ? $candidato : $campo;
    }
}
