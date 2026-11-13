<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\Expediente;
use App\Models\TablaDeMunicipio;
use Illuminate\Validation\ValidationException;

/**
 * Completa la instantánea de contexto de obra requerida por el Modelo 0 de aprobación.
 */
final class ModeloCeroAprobacionContextService
{
    /**
     * @param  array<string, string|int|float|bool|null>  $data
     * @return array<string, string|int|float|bool|null>
     *
     * @throws ValidationException
     */
    public function enrich(array $data): array
    {
        $expedienteId = trim((string) ($data['expediente_id'] ?? ''));
        $context = $this->contextForExpediente($expedienteId);

        return [
            ...$data,
            ...$context,
            'anualidad' => $data['anualidad'] ?? $context['ao_ejecucion'],
        ];
    }

    /**
     * Obtiene el contexto de obra y los datos institucionales del ayuntamiento.
     *
     * @return array<string, string|int|null>
     *
     * @throws ValidationException
     */
    public function contextForExpediente(string $expedienteId): array
    {
        $expediente = Expediente::query()
            ->with(['planes', 'team'])
            ->where('expediente_id', $expedienteId)
            ->first();

        if (! $expediente instanceof Expediente) {
            throw ValidationException::withMessages([
                'expediente_id' => 'El expediente seleccionado no existe.',
            ]);
        }

        $codigoMunicipio = trim((string) $expediente->getAttribute('municipio'));
        $ayuntamiento = $codigoMunicipio === ''
            ? null
            : TablaDeMunicipio::query()
                ->where('codigo_municipio', $codigoMunicipio)
                ->first();

        $entidadLocal = trim((string) $ayuntamiento?->getAttribute('nombre_municipio'));
        $secretario = trim((string) $ayuntamiento?->getAttribute('Secretario'));

        $codigoPlan = $this->stringAttribute($expediente, ['codigo_plan', 'Codigo_Plan']);
        $numeroObra = $this->integerAttribute($expediente, ['numero_obra', 'referencia']);
        $subreferencia = $this->integerAttribute($expediente, ['subreferencia', 'subreferecnia']);
        $aoEjecucion = $this->integerAttribute($expediente, ['ao_ejecucion']);
        $nombreObra = $this->stringAttribute($expediente, ['nombre_obra', 'nombre_obra1']);

        return [
            'expediente_id' => $expedienteId,
            'codigo_plan' => $codigoPlan,
            'numero_obra' => $numeroObra,
            'subreferencia' => $subreferencia,
            'ao_ejecucion' => $aoEjecucion,
            'nombre_obra' => $nombreObra,
            'programa_denominacion' => $expediente->planes?->denominacion_plan,
            'proyecto_actuacion' => $nombreObra,
            'referencia_actuacion' => $expedienteId,
            'entidad_local' => $entidadLocal,
            'secretario_nombre' => $secretario,
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
