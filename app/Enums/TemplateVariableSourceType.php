<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Tipos de origen soportados para resolver variables de una plantilla Word.
 *
 * Permite distinguir entre valores deducidos automáticamente, campos del
 * modelo actual, valores de sistema y constantes configuradas manualmente.
 */
enum TemplateVariableSourceType: string
{
    case AUTO = 'auto';
    case RECORD = 'record';
    case SYSTEM = 'system';
    case FIXED = 'fixed';

    /**
     * Devuelve las opciones legibles para formularios y tablas de Filament.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::AUTO->value => 'Automático',
            self::RECORD->value => 'Campo del registro',
            self::SYSTEM->value => 'Valor del sistema',
            self::FIXED->value => 'Valor fijo',
        ];
    }
}
