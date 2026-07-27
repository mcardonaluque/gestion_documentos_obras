<?php

declare(strict_types=1);

namespace App\Services\Prorrogas;

use App\DTOs\Prorrogas\ProrrogaValidationResult;
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
}
