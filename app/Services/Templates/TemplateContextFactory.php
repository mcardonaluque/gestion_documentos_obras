<?php

declare(strict_types=1);

namespace App\Services\Templates;

use Illuminate\Database\Eloquent\Model;

/**
 * Fabrica el contexto base que será usado para resolver variables de plantilla.
 *
 * Su cometido es precargar relaciones relevantes del modelo recibido y aportar
 * metadatos comunes como la fecha de generación o los parámetros manuales.
 */
final class TemplateContextFactory
{
    /**
     * Construye el contexto compartido para una impresión documental.
     *
     * method_exists se usa para cargar solo relaciones realmente definidas en
     * el modelo recibido, evitando errores al reutilizar el servicio con
     * distintos Resources y tipos de registro.
     *
     * @param array<string, string|int|float|bool|null> $input
     * @return array<string, \DateTimeInterface|array<string, string|int|float|bool|null>|string>
     */
    public function build(Model $record, array $input = []): array
    {
        $relations = array_values(array_filter([
            method_exists($record, 'expediente') ? 'expediente' : null,
            method_exists($record, 'municipios') ? 'municipios' : null,
            method_exists($record, 'planes') ? 'planes' : null,
            method_exists($record, 'importes') ? 'importes' : null,
            method_exists($record, 'importesPorOrganismo') ? 'importesPorOrganismo' : null,
            method_exists($record, 'ejecucion') ? 'ejecucion' : null,
            method_exists($record, 'ayudaTecnica') ? 'ayudaTecnica' : null,
            method_exists($record, 'team') ? 'team' : null,
        ]));

        if ($relations !== []) {
            $record->loadMissing($relations);
        }

        return [
            'input' => $input,
            'generated_at' => now(),
            'record_class' => $record::class,
        ];
    }
}
