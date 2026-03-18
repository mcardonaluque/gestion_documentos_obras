<?php

declare(strict_types=1);

namespace App\Enums;

enum DateRuleType: string
{
    case WARNING = 'warning';
    case ERROR = 'error';
    case NOTIFICATION = 'notification';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::WARNING->value => 'Advertencia',
            self::ERROR->value => 'Error',
            self::NOTIFICATION->value => 'Notificacion',
        ];
    }

    public function isBlocking(): bool
    {
        return $this === self::ERROR;
    }
}
