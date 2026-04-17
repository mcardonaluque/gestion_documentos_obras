<?php

declare(strict_types=1);

namespace App\Enums;

enum ImportesStage: string
{
    case INICIO = 'inicio';
    case CESION = 'cesion';
    case CONTRATACION = 'contratacion';
    case EJECUCION = 'ejecucion';

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
