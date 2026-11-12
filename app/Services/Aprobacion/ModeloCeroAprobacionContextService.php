<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\Expediente;
use Illuminate\Validation\ValidationException;

/**
 * Completa la instantánea de contexto de obra requerida por el Modelo 0 de aprobación.
 */
final class ModeloCeroAprobacionContextService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    public function enrich(array $data): array
    {
        $expedienteId = trim((string) ($data['expediente_id'] ?? ''));
        $expediente = Expediente::query()
            ->with(['planes', 'team'])
            ->where('expediente_id', $expedienteId)
            ->first();

        if (! $expediente instanceof Expediente) {
            throw ValidationException::withMessages([
                'expediente_id' => 'El expediente seleccionado no existe.',
            ]);
        }

        $codigoPlan = $this->stringAttribute($expediente, ['codigo_plan', 'Codigo_Plan']);
        $numeroObra = $this->integerAttribute($expediente, ['numero_obra', 'referencia']);
        $subreferencia = $this->integerAttribute($expediente, ['subreferencia', 'subreferecnia']);
        $aoEjecucion = $this->integerAttribute($expediente, ['ao_ejecucion']);
        $nombreObra = $this->stringAttribute($expediente, ['nombre_obra', 'nombre_obra1']);

        return [
            ...$data,
            'expediente_id' => $expedienteId,
            'codigo_plan' => $codigoPlan,
            'numero_obra' => $numeroObra,
            'subreferencia' => $subreferencia,
            'ao_ejecucion' => $aoEjecucion,
            'nombre_obra' => $nombreObra,
            'anualidad' => $data['anualidad'] ?? $aoEjecucion,
            'programa_denominacion' => $expediente->planes?->denominacion_plan,
            'proyecto_actuacion' => $nombreObra,
            'referencia_actuacion' => $expedienteId,
            'entidad_local' => $data['entidad_local'] ?? $expediente->team?->name,
            'team_id' => $expediente->team_id,
        ];
    }

    /**
     * @param  list<string>  $attributes
     */
    private function stringAttribute(Expediente $expediente, array $attributes): ?string
    {
        foreach ($attributes as $attribute) {
            $value = $expediente->getAttribute($attribute);

            if (is_scalar($value) && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }

    /**
     * @param  list<string>  $attributes
     */
    private function integerAttribute(Expediente $expediente, array $attributes): ?int
    {
        foreach ($attributes as $attribute) {
            $value = $expediente->getAttribute($attribute);

            if (is_numeric($value)) {
                return (int) $value;
            }
        }

        return null;
    }
}
