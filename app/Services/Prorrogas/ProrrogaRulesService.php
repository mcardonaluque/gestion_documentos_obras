<?php

declare(strict_types=1);

namespace App\Services\Prorrogas;

use App\DTOs\Prorrogas\ProrrogaValidationResult;
use App\Enums\ProrrogaTipo;
use App\Models\Expediente;
use App\Models\NormativaPpac;
use App\Models\PlazoObraActivo;
use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Reglas funcionales de admisión de prórrogas.
 *
 * Esta clase centraliza las comprobaciones de negocio para mantener la lógica
 * de validación fuera de los recursos Filament y de la acción de persistencia.
 */
final class ProrrogaRulesService
{
    /** Antelación mínima, en días naturales, con la que debe pedirse cualquier prórroga. */
    public const DIAS_ANTELACION_MINIMA = 15;

    /**
     * Calcula la ventana de solicitud de prórroga (fase, tipo, fecha límite
     * y fecha máxima de solicitud) según la normativa PPAC aplicable al plazo.
     *
     * @return array{fase: string, tipo: string, fecha_limite: ?Carbon, fecha_maxima_solicitud: ?Carbon, normativa: ?NormativaPpac}
     */
    public function getRequestWindow(Expediente $expediente, PlazoObraActivo $plazo): array
    {
        $normativa = $plazo->normativa
            ?? NormativaPpac::resolveFor($expediente->ao_ejecucion, $expediente->codigo_plan);
        $fase = (string) $plazo->fase;
        $redaccionDiputacion = in_array($fase, ['proyecto_memoria', 'documentacion'], true)
            && (bool) ($expediente->proyecto?->SubvencionEconRedaccion ?? false);

        $fechaLimite = in_array($fase, ['proyecto_memoria', 'documentacion', 'ejecucion', 'justificacion'], true)
            ? $this->resolveNormativaDeadline($normativa, $fase, $redaccionDiputacion)
            : ($plazo->fecha_fin ? Carbon::parse($plazo->fecha_fin)->endOfDay() : null);

        return [
            'fase' => $fase,
            'tipo' => match ($fase) {
                'proyecto_memoria' => 'proyecto',
                'documentacion' => 'documentacion',
                'ejecucion' => 'ejecucion',
                'justificacion' => 'justificacion',
                default => 'normativa',
            },
            'fecha_limite' => $fechaLimite,
            'fecha_maxima_solicitud' => $fechaLimite?->copy()->subDays(15),
            'normativa' => $normativa,
        ];
    }

    /**
     * Fecha límite inicial aprobada en la normativa PPAC para una fase concreta.
     *
     * Para proyecto/documentación distingue entre redacción por la Diputación (D)
     * o por el Ayuntamiento (A); para ejecución usa la fecha límite de terminación
     * del plan y para justificación su fecha límite específica.
     */
    public function resolveNormativaDeadline(
        ?NormativaPpac $normativa,
        string $fase,
        bool $redaccionDiputacion = false,
    ): ?Carbon {
        $fechaLimite = match ($fase) {
            'proyecto_memoria' => $redaccionDiputacion
                ? $normativa?->fecha_limite_presentacion_proyectoD
                : $normativa?->fecha_limite_presentacion_proyectoA,
            'documentacion' => $redaccionDiputacion
                ? $normativa?->fecha_limite_presentacion_documentacionD
                : $normativa?->fecha_limite_presentacion_documentacionA,
            'ejecucion' => $normativa?->fecha_limite_terminacion_plan,
            'justificacion' => $normativa?->fecha_limite_justificacion,
            default => null,
        };

        return $fechaLimite ? Carbon::parse($fechaLimite)->endOfDay() : null;
    }

    /**
     * Valida que el plazo inicial aprobado en la normativa no haya vencido
     * en la fecha de la petición de prórroga.
     *
     * Solo aplica cuando el plazo activo no ha sido ampliado previamente por
     * otra prórroga (en ese caso la fecha operativa pasa a ser la fecha fin
     * ampliada del plazo, que ya se controla en validateAdmission()).
     */
    public function validateNormativaDeadline(
        PlazoObraActivo $plazo,
        ?CarbonInterface $normativaDeadline,
        CarbonInterface $requestDate,
    ): ProrrogaValidationResult {
        $maxAllowedDays = (int) floor($plazo->dias_base / 2);

        if ($normativaDeadline === null) {
            return new ProrrogaValidationResult(true, null, $maxAllowedDays);
        }

        // El plazo ya fue ampliado por una prórroga anterior: rige la fecha fin vigente.
        if (($plazo->fuente_ultima_actualizacion ?? null) === 'prorroga') {
            return new ProrrogaValidationResult(true, null, $maxAllowedDays);
        }

        if ($requestDate->greaterThan($normativaDeadline)) {
            $faseLabel = match ((string) $plazo->fase) {
                'proyecto_memoria' => 'presentación de proyecto/memoria',
                'documentacion' => 'presentación de documentación',
                'ejecucion' => 'ejecución',
                'justificacion' => 'justificación',
                default => (string) $plazo->fase,
            };

            return new ProrrogaValidationResult(
                false,
                "No se puede registrar la prórroga: el plazo de {$faseLabel} aprobado en la normativa venció el "
                    . $normativaDeadline->format('d/m/Y') . '.',
                $maxAllowedDays,
            );
        }

        return new ProrrogaValidationResult(true, null, $maxAllowedDays);
    }

    /**
     * Valida si una prórroga es admisible sobre un plazo activo.
     *
     * Reglas, en este orden:
     * 1. La nueva fecha límite debe ser posterior al plazo vigente.
     * 2. Justificación: solo puede pedirse una vez vencido el plazo de ejecución
     *    vigente (incluidas sus prórrogas).
     * 3. Cualquier prórroga se solicita con al menos {@see self::DIAS_ANTELACION_MINIMA}
     *    días naturales de antelación sobre el plazo vigente.
     * 4. Máximo concedible:
     *    - ejecución: la mitad del plazo inicialmente concedido (dias_base);
     *    - justificación: un mes sobre el plazo vigente;
     *    - resto (aprobación): la mitad del plazo base.
     *
     * @param PlazoObraActivo $plazo Plazo activo que se pretende ampliar.
     * @param PlazoObraActivo|null $plazoEjecucion Plazo activo de ejecución (necesario para justificación).
     * @param CarbonInterface $requestDate Fecha de la solicitud.
     * @param CarbonInterface $newLimit Nueva fecha límite solicitada.
     */
    public function validateAdmission(
        ProrrogaTipo $tipo,
        PlazoObraActivo $plazo,
        ?PlazoObraActivo $plazoEjecucion,
        CarbonInterface $requestDate,
        CarbonInterface $newLimit,
    ): ProrrogaValidationResult {
        $fechaFin = Carbon::parse((string) $plazo->fecha_fin)->startOfDay();
        $nuevaFecha = Carbon::parse($newLimit)->startOfDay();
        $solicitud = Carbon::parse($requestDate)->startOfDay();
        $diasSolicitados = (int) $fechaFin->diffInDays($nuevaFecha, false);
        $maxAllowedDays = $this->maxAllowedDays($tipo, $plazo);

        if ($diasSolicitados <= 0) {
            return new ProrrogaValidationResult(
                false,
                'La nueva fecha límite debe ser posterior al plazo vigente (' . $fechaFin->format('d/m/Y') . ').',
                $maxAllowedDays,
            );
        }

        if ($tipo === ProrrogaTipo::JUSTIFICACION) {
            if ($plazoEjecucion === null || $plazoEjecucion->fecha_fin === null) {
                return new ProrrogaValidationResult(
                    false,
                    'No consta el plazo de ejecución de la obra: la prórroga de justificación solo puede pedirse una vez vencido.',
                    $maxAllowedDays,
                );
            }

            $finEjecucion = Carbon::parse((string) $plazoEjecucion->fecha_fin)->startOfDay();

            if ($solicitud->lessThanOrEqualTo($finEjecucion)) {
                return new ProrrogaValidationResult(
                    false,
                    'La prórroga de justificación no puede pedirse hasta que venza el plazo de ejecución ('
                        . $finEjecucion->format('d/m/Y') . ').',
                    $maxAllowedDays,
                );
            }
        }

        $fechaMaximaSolicitud = $fechaFin->copy()->subDays(self::DIAS_ANTELACION_MINIMA);

        if ($solicitud->greaterThan($fechaMaximaSolicitud)) {
            return new ProrrogaValidationResult(
                false,
                'La prórroga debe solicitarse con al menos ' . self::DIAS_ANTELACION_MINIMA
                    . ' días naturales de antelación sobre el plazo vigente ('
                    . $fechaFin->format('d/m/Y') . '): último día para pedirla, '
                    . $fechaMaximaSolicitud->format('d/m/Y') . '.',
                $maxAllowedDays,
            );
        }

        if ($tipo === ProrrogaTipo::JUSTIFICACION) {
            $fechaMaxima = $fechaFin->copy()->addMonthNoOverflow();

            if ($nuevaFecha->greaterThan($fechaMaxima)) {
                return new ProrrogaValidationResult(
                    false,
                    'La prórroga de justificación no puede exceder un mes: fecha máxima ' . $fechaMaxima->format('d/m/Y') . '.',
                    $maxAllowedDays,
                );
            }

            return new ProrrogaValidationResult(true, null, $maxAllowedDays);
        }

        if ($maxAllowedDays <= 0) {
            return new ProrrogaValidationResult(
                false,
                'No consta el plazo inicialmente concedido (días base) para calcular el máximo de la prórroga.',
                $maxAllowedDays,
            );
        }

        if ($diasSolicitados > $maxAllowedDays) {
            return new ProrrogaValidationResult(
                false,
                $tipo === ProrrogaTipo::EJECUCION
                    ? "La prórroga de ejecución no puede exceder la mitad del plazo inicialmente concedido ({$maxAllowedDays} días)."
                    : "La prórroga no puede exceder la mitad del plazo base ({$maxAllowedDays} días).",
                $maxAllowedDays,
            );
        }

        return new ProrrogaValidationResult(true, null, $maxAllowedDays);
    }

    /**
     * Días máximos concedibles según el tipo (para justificación, los días del mes siguiente).
     */
    private function maxAllowedDays(ProrrogaTipo $tipo, PlazoObraActivo $plazo): int
    {
        if ($tipo === ProrrogaTipo::JUSTIFICACION) {
            $fechaFin = Carbon::parse((string) $plazo->fecha_fin)->startOfDay();

            return (int) $fechaFin->diffInDays($fechaFin->copy()->addMonthNoOverflow());
        }

        return (int) floor(max(0, (int) ($plazo->dias_base ?? 0)) / 2);
    }

    /**
     * Calcula la fecha límite de justificación: tres meses tras el nuevo límite de ejecución.
     */
    public function calculateJustificationDeadline(CarbonInterface $newExecutionLimit): Carbon
    {
        return Carbon::parse($newExecutionLimit)->copy()->addMonthsNoOverflow(3)->endOfDay();
    }

    /**
     * Indica si se permite subir justificación: solo bloquea cuando el plazo activo
     * de fase justificación está vencido en la fecha de subida.
     */
    public function allowJustificationUpload(PlazoObraActivo $plazo, CarbonInterface $uploadDate): bool
    {
        if (($plazo->fase ?? '') !== 'justificacion' || ! (bool) ($plazo->activo ?? false)) {
            return true;
        }

        $deadline = $plazo->fecha_fin
            ? Carbon::parse((string) $plazo->fecha_fin)->endOfDay()
            : $uploadDate->copy()->endOfDay();

        return $uploadDate->lessThanOrEqualTo($deadline);
    }
}
