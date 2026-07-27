<?php

declare(strict_types=1);

namespace App\Enums;

enum ProrrogaAlcance: string
{
    /** Afecta al proyecto o memoria técnica. */
    case PROYECTO_MEMORIA = 'proyecto_memoria';
    /** Afecta a la documentación aportada. */
    case DOCUMENTACION = 'documentacion';
    /** Afecta a ambos ámbitos. */
    case AMBAS = 'ambas';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::PROYECTO_MEMORIA->value => 'Presentación de proyecto/memoria',
            self::DOCUMENTACION->value => 'Presentación de documentación',
            self::AMBAS->value => 'Proyecto/memoria y documentación',
        ];
    }
}
