<?php

declare(strict_types=1);

namespace App\Services\Aprobacion;

use App\Models\DatosDeInicioDeObras;
use App\Models\TablaDeMunicipio;
use Illuminate\Validation\ValidationException;

final class ModeloDosAprobacionContextService
{
    /** @param array<string, mixed> $data @return array<string, mixed> */
    public function enrich(array $data): array
    {
        $context = $this->contextForExpediente(trim((string) ($data['expediente_id'] ?? '')));
        $secretario = trim((string) ($data['secretario_nombre'] ?? ''));

        return [
            ...$context,
            ...$data,
            'team_id' => $context['team_id'],
            'entidad_local' => $context['entidad_local'],
            'secretario_nombre' => $secretario !== '' ? $secretario : $context['secretario_nombre'],
            'fecha_documento' => now(),
        ];
    }

    /** @return array<string, int|string|null> */
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
            'codigo_plan' => trim((string) $obra->getAttribute('Codigo_Plan')),
            'numero_obra' => (int) $obra->getAttribute('numero_obra'),
            'subreferencia' => (int) $obra->getAttribute('subreferencia'),
            'ao_ejecucion' => (int) $obra->getAttribute('ao_ejecucion'),
            'nombre_obra' => $this->nombreObra($obra),
            'codigo_municipio' => (int) $obra->getAttribute('municipio'),
            'entidad_local' => trim((string) $municipio?->getAttribute('nombre_municipio')),
            'secretario_nombre' => trim((string) $municipio?->getAttribute('Secretario')),
            'team_id' => (int) $obra->getAttribute('team_id'),
        ];
    }

    private function nombreObra(DatosDeInicioDeObras $obra): ?string
    {
        $partes = array_filter([
            trim((string) $obra->getAttribute('nombre_obra1')),
            trim((string) $obra->getAttribute('nombre_obra2')),
            trim((string) $obra->getAttribute('nombre_obra3')),
        ]);

        return $partes === [] ? null : implode(' ', $partes);
    }
}
