<?php

declare(strict_types=1);

namespace App\Services\Prorrogas;

use App\Models\NormativaPpac;
use App\Models\PlazoObraActivo;
use Carbon\Carbon;

final class SolicitudProrrogaService
{
    /**
     * Construye los datos de una solicitud a partir del plazo activo y la información aportada.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function buildRequestData(PlazoObraActivo $plazo, ?NormativaPpac $normativa, array $data): array
    {
        $fechaAnterior = Carbon::parse((string) $plazo->fecha_fin)->copy()->endOfDay();
        $fechaSolicitada = $this->normalizeDate($data['fecha_limite_solicitada'] ?? null);
        $diasSolicitados = isset($data['dias_solicitados']) && $data['dias_solicitados'] !== null
            ? (int) $data['dias_solicitados']
            : null;

        if ($fechaSolicitada === null && $diasSolicitados === null) {
            return [
                'valid' => false,
                'message' => 'Debe indicar una fecha límite o un número de días solicitados.',
                'dias_solicitados' => 0,
                'fecha_limite_solicitada' => null,
                'fecha_limite_anterior' => $fechaAnterior->toDateString(),
                'fecha_limite_nueva' => $fechaAnterior->toDateString(),
            ];
        }

        if ($fechaSolicitada !== null) {
            $diasSolicitados = (int) $fechaAnterior->copy()->startOfDay()->diffInDays($fechaSolicitada->copy()->startOfDay()) + 1;
        }

        $maxAllowedDays = $this->getMaxAllowedDays($plazo, $normativa);

        if ($diasSolicitados === null || $diasSolicitados <= 0) {
            return [
                'valid' => false,
                'message' => 'Los días concedidos deben ser mayores que cero.',
                'dias_solicitados' => 0,
                'fecha_limite_solicitada' => $fechaSolicitada,
                'fecha_limite_anterior' => $fechaAnterior->toDateString(),
                'fecha_limite_nueva' => $fechaAnterior->toDateString(),
            ];
        }

        if ($diasSolicitados > $maxAllowedDays) {
            return [
                'valid' => false,
                'message' => "La solicitud supera el máximo permitido por la normativa ({$maxAllowedDays} días).",
                'dias_solicitados' => $diasSolicitados,
                'fecha_limite_solicitada' => $fechaSolicitada,
                'fecha_limite_anterior' => $fechaAnterior->toDateString(),
                'fecha_limite_nueva' => $fechaAnterior->toDateString(),
            ];
        }

        $fechaNueva = $fechaSolicitada ?? $fechaAnterior->copy()->addDays($diasSolicitados - 1);

        return [
            'valid' => true,
            'message' => null,
            'dias_solicitados' => $diasSolicitados,
            'fecha_limite_solicitada' => $fechaNueva,
            'fecha_limite_anterior' => $fechaAnterior->toDateString(),
            'fecha_limite_nueva' => $fechaNueva->toDateString(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validatePhaseRestriction(PlazoObraActivo $plazo, string $requestedType): array
    {
        $fase = (string) ($plazo->fase ?? '');

        if ($fase === 'ejecucion' && in_array($requestedType, ['proyecto', 'documentacion', 'proyectodc'], true)) {
            return [
                'valid' => false,
                'message' => 'No se pueden solicitar prórrogas de proyecto o documentación mientras el expediente esté en fase de ejecución.',
            ];
        }

        if ($fase === 'justificacion' && $requestedType === 'ejecucion') {
            return [
                'valid' => false,
                'message' => 'No se pueden solicitar prórrogas de ejecución mientras el expediente esté en fase de justificación.',
            ];
        }

        return [
            'valid' => true,
            'message' => null,
        ];
    }

    private function getMaxAllowedDays(PlazoObraActivo $plazo, ?NormativaPpac $normativa): int
    {
        $baseDays = max(0, (int) ($plazo->dias_base ?? 0));

        if ($normativa === null) {
            return (int) floor($baseDays / 2);
        }

        $percent = max(0, (int) ($normativa->dias_prorroga_max_porcentaje ?? 0));

        return (int) floor($baseDays * $percent / 100);
    }

    private function normalizeDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse((string) $value)->copy()->startOfDay();
    }
}
