<?php

declare(strict_types=1);

namespace App\Services\Prorrogas;

use App\DTOs\Prorrogas\ProrrogaValidationResult;
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
     * ampliada del plazo, que ya se controla en validateRequest()).
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
     * Valida si una solicitud de prórroga es admisible para un plazo activo.
     *
     * @param PlazoObraActivo $plazo Plazo activo sobre el que se pretende aplicar la prórroga.
     * @param CarbonInterface $requestDate Fecha en la que se formula la petición.
     * @param int $requestedDays Número de días solicitados o calculados para la ampliación.
     * @param bool $isSuccessiveExecution True cuando la prórroga es sucesiva de ejecución.
     * @return ProrrogaValidationResult Resultado estructurado con estado, mensaje y máximo permitido.
     */
    public function validateRequest(
        PlazoObraActivo $plazo,
        CarbonInterface $requestDate,
        int $requestedDays,
        bool $isSuccessiveExecution,
    ): ProrrogaValidationResult {
        $maxAllowedDays = (int) floor($plazo->dias_base / 2);

        if ($requestedDays <= 0) {
            return new ProrrogaValidationResult(false, 'Los días concedidos deben ser mayores que cero.', $maxAllowedDays);
        }

        if ($requestedDays > $maxAllowedDays) {
            return new ProrrogaValidationResult(
                false,
                "La prórroga no puede exceder la mitad del plazo base ({$maxAllowedDays} días).",
                $maxAllowedDays,
            );
        }

        $fechaFin = Carbon::parse((string) $plazo->fecha_fin);

        $limitDate = $isSuccessiveExecution
            ? $fechaFin->copy()->subDay()
            : $fechaFin->copy()->subDays(15);

        if ($requestDate->greaterThan($limitDate)) {
            return new ProrrogaValidationResult(
                false,
                'La solicitud está fuera de plazo para esta fase.',
                $maxAllowedDays,
            );
        }

        return new ProrrogaValidationResult(true, null, $maxAllowedDays);
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
