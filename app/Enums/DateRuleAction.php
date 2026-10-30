<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Acción a ejecutar cuando se cumple una regla de validación de fechas.
 *
 * Lo usan el modelo `DateValidationRule`, el recurso de Filament
 * `DateValidationRuleResource` y `DateRuleNotifier`, que decide si
 * notificar, generar o pedir un documento al dispararse la regla.
 */
enum DateRuleAction: string
{
    case NONE = 'none';
    case NOTIFY = 'notify';
    case GENERATE_DOCUMENT = 'generate_document';
    case REQUEST_DOCUMENT = 'request_document';

    /**
     * Opciones legibles (valor => etiqueta) para selects de Filament.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::NONE->value => 'Ninguna',
            self::NOTIFY->value => 'Notificar',
            self::GENERATE_DOCUMENT->value => 'Generar documento',
            self::REQUEST_DOCUMENT->value => 'Pedir documento',
        ];
    }
}
