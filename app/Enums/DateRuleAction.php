<?php

declare(strict_types=1);

namespace App\Enums;

enum DateRuleAction: string
{
    case NONE = 'none';
    case NOTIFY = 'notify';
    case GENERATE_DOCUMENT = 'generate_document';
    case REQUEST_DOCUMENT = 'request_document';

    /**
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
