<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

/**
 * Aplica a la respuesta simulada de OBDOC los indicadores de la consulta:
 * original, caratula y metadatos (true/false). Si un indicador no es true,
 * el bloque correspondiente se omite igual que hace la API real.
 */
final class DocumentoMock
{
    /** Indicador de la consulta => campo de data que controla. */
    private const BLOQUES = [
        'original' => 'original_contenido',
        'caratula' => 'caratula_contenido',
        'metadatos' => 'metadatos',
    ];

    /**
     * @param  array<string, mixed>  $cuerpo  Contenido del JSON base.
     * @param  array<string, mixed>  $query  Parámetros de la petición.
     * @return array<string, mixed>
     */
    public static function aplicar(array $cuerpo, array $query): array
    {
        if (is_array($cuerpo['data'] ?? null)) {
            foreach (self::BLOQUES as $indicador => $campo) {
                if (! self::activo($query[$indicador] ?? null)) {
                    unset($cuerpo['data'][$campo]);
                }
            }
        }

        return $cuerpo;
    }

    private static function activo(mixed $valor): bool
    {
        return in_array($valor, [true, 1, '1', 'true'], true);
    }
}
