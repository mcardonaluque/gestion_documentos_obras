<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

use RuntimeException;

/**
 * Carga las respuestas simuladas del tramitador desde ficheros JSON.
 *
 * Estructura: resources/mock/tramitador/{CODIGO_OPERACION}/{id}.json.
 * Si no existe el fichero del id solicitado se usa default.json, sustituyendo
 * data.id_exp por el id pedido. Para añadir un escenario basta crear un JSON.
 */
final class TramitadorMockFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function respuesta(string $operacion, int|string $id): array
    {
        $directorio = resource_path("mock/tramitador/{$operacion}");
        $fichero = "{$directorio}/{$id}.json";

        if (! is_file($fichero) && ! ctype_digit((string) $id)) {
            $fichero = self::buscarPorCsv($directorio, (string) $id) ?? $fichero;
        }

        $esDefecto = ! is_file($fichero);
        $contenido = self::leer($esDefecto ? "{$directorio}/default.json" : $fichero);

        if ($esDefecto && isset($contenido['data']) && is_array($contenido['data'])) {
            $contenido['data'] = self::sustituirId($contenido['data'], (int) $id);
        }

        return $contenido;
    }

    /**
     * Sustituye en el JSON por defecto el identificador que la operación expone.
     *
     * @param  array<array-key, mixed>  $data
     * @return array<array-key, mixed>
     */
    private static function sustituirId(array $data, int $id): array
    {
        foreach (['id_exp', 'id_doc', 'id_peticion'] as $clave) {
            if (array_key_exists($clave, $data)) {
                $data[$clave] = $id;
            }
        }

        return $data;
    }

    /** Permite localizar un fichero por su CSV (data.csv) cuando el id no es numérico. */
    private static function buscarPorCsv(string $directorio, string $csv): ?string
    {
        $encontrado = null;

        foreach (glob("{$directorio}/*.json") ?: [] as $fichero) {
            $datos = json_decode((string) file_get_contents($fichero), true);

            if (is_array($datos) && ($datos['data']['csv'] ?? null) === $csv) {
                $encontrado = $fichero;
                break;
            }
        }

        return $encontrado;
    }

    /**
     * @return array<string, mixed>
     */
    private static function leer(string $ruta): array
    {
        $json = is_file($ruta) ? file_get_contents($ruta) : false;
        $datos = $json === false ? null : json_decode($json, true);

        if (! is_array($datos)) {
            throw new RuntimeException("Fixture del mock inválido o inexistente: {$ruta}");
        }

        /** @var array<string, mixed> $datos */
        return $datos;
    }
}
