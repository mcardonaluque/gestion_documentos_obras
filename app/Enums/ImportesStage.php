<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Fase económica de la obra sobre la que se gestionan los importes:
 * inicio/aprobación, cesión, contratación o ejecución.
 *
 * Lo usan el componente de formulario `ImportesManagement`, el widget
 * Livewire `ManageImportesWidget` y `ImportesDistributionCalculator`
 * para saber qué columnas de importes/porcentajes son editables.
 */
enum ImportesStage: string
{
    case INICIO = 'inicio';
    case CESION = 'cesion';
    case CONTRATACION = 'contratacion';
    case EJECUCION = 'ejecucion';

    /**
     * Etiqueta legible de la fase.
     */
    public function label(): string
    {
        return match ($this) {
            self::INICIO => 'Inicio y aprobacion',
            self::CESION => 'Cesion',
            self::CONTRATACION => 'Contratacion',
            self::EJECUCION => 'Ejecucion',
        };
    }

    /**
     * Opciones (valor => etiqueta) para selects de fase.
     *
     * @return array<int, string>
     */
    public static function options(): array
    {
        return [
            self::INICIO->value => self::INICIO->label(),
            self::CESION->value => self::CESION->label(),
            self::CONTRATACION->value => self::CONTRATACION->label(),
            self::EJECUCION->value => self::EJECUCION->label(),
        ];
    }
}
