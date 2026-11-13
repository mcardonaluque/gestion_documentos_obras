<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\DatosDeInicioDeObras;
use App\Models\TablaDeMunicipio;
use Illuminate\Validation\ValidationException;

final class ModeloUnoAprobacionContextService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    public function enrich(array $data): array
    {
        $context = $this->contextForExpediente(trim((string) ($data['expediente_id'] ?? '')));

        return [
            ...$context,
            ...$data,
            'team_id' => $context['team_id'],
            'entidad_local' => $context['entidad_local'],
            'secretario_nombre' => $this->secretarioNombre($data, $context),
        ];
    }

    /**
     * @return array<string, int|string|null>
     *
     * @throws ValidationException
     */
    public function contextForExpediente(string $expedienteId): array
    {
        $obra = DatosDeInicioDeObras::query()
            ->where('expediente_id', $expedienteId)
            ->first();

        if (! $obra instanceof DatosDeInicioDeObras) {
            throw ValidationException::withMessages([
                'expediente_id' => 'El expediente seleccionado no tiene datos de inicio de obra.',
            ]);
        }

        $municipio = TablaDeMunicipio::query()
            ->where('codigo_municipio', $obra->getAttribute('municipio'))
            ->first();

        return [
            'expediente_id' => $expedienteId,
            'codigo_plan' => $this->stringAttribute($obra, ['Codigo_Plan', 'codigo_plan']),
            'numero_obra' => $this->integerAttribute($obra, ['numero_obra']),
            'subreferencia' => $this->integerAttribute($obra, ['subreferencia']),
            'ao_ejecucion' => $this->integerAttribute($obra, ['ao_ejecucion']),
            'nombre_obra' => $this->nombreObra($obra),
            'entidad_local' => trim((string) $municipio?->getAttribute('nombre_municipio')),
            'secretario_nombre' => trim((string) $municipio?->getAttribute('Secretario')),
            'team_id' => $this->integerAttribute($obra, ['team_id']),
        ];
    }

    private function nombreObra(DatosDeInicioDeObras $obra): ?string
    {
        $partes = array_filter([
            $this->stringAttribute($obra, ['nombre_obra1']),
            $this->stringAttribute($obra, ['nombre_obra2']),
            $this->stringAttribute($obra, ['nombre_obra3']),
        ]);

        return $partes === [] ? null : implode(' ', $partes);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, int|string|null>  $context
     */
    private function secretarioNombre(array $data, array $context): string
    {
        $secretario = trim((string) ($data['secretario_nombre'] ?? ''));

        return $secretario !== ''
            ? $secretario
            : trim((string) ($context['secretario_nombre'] ?? ''));
    }

    /** @param list<string> $attributes */
    private function stringAttribute(DatosDeInicioDeObras $obra, array $attributes): ?string
    {
        foreach ($attributes as $attribute) {
            $value = $obra->getAttribute($attribute);
            if (is_scalar($value) && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }

    /** @param list<string> $attributes */
    private function integerAttribute(DatosDeInicioDeObras $obra, array $attributes): ?int
    {
        foreach ($attributes as $attribute) {
            $value = $obra->getAttribute($attribute);
            if (is_numeric($value)) {
                return (int) $value;
            }
        }

        return null;
    }
}
