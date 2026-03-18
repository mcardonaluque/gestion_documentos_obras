<?php

declare(strict_types=1);

namespace App\Enums;

enum DateRuleOperation: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case BOTH = 'both';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::CREATE->value => 'Alta',
            self::UPDATE->value => 'Actualizacion',
            self::BOTH->value => 'Alta y actualizacion',
        ];
    }

    public static function fromModelState(bool $exists): self
    {
        return $exists ? self::UPDATE : self::CREATE;
    }
}
