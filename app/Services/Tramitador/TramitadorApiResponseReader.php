<?php

declare(strict_types=1);

namespace App\Services\Tramitador;

use App\Models\TramitadorApiOperacion;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * Interpreta las respuestas del tramitador con el contrato fijo:
 * { status, message, data }.
 *
 * status y message no se definen en el catálogo. Los parámetros activos de
 * salida se extraen exclusivamente de data mediante su ruta_json.
 */
class TramitadorApiResponseReader
{
    public function leer(string $codigoOperacion, Response $response): array
    {
        $operacion = TramitadorApiOperacion::query()
            ->where('codigo', $codigoOperacion)
            ->with(['parametros' => fn ($query) => $query->where('activo', true)->orderBy('orden')])
            ->first();

        if ($operacion === null) {
            throw new InvalidArgumentException("La función API [{$codigoOperacion}] no existe.");
        }

        $contenido = $response->json();
        // Si data falta, las rutas configuradas devolverán su valor por defecto.
        $data = Arr::get($contenido, 'data', []);
        $resultado = [
            'status' => Arr::get($contenido, 'status'),
            'message' => Arr::get($contenido, 'message'),
            'data' => [],
        ];

        foreach ($operacion->parametros->where('direccion', 'salida') as $parametro) {
            $ruta = $parametro->ruta_json ?: $parametro->nombre;
            // ruta_json es relativa a data; se tolera el prefijo antiguo data.
            $ruta = str_starts_with($ruta, 'data.') ? substr($ruta, 5) : $ruta;

            $resultado['data'][$parametro->nombre] = Arr::get(
                $data,
                $ruta,
                $parametro->valor_por_defecto,
            );
        }

        return $resultado;
    }
}
