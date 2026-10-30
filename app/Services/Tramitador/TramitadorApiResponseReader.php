<?php

declare(strict_types=1);

namespace App\Services\Tramitador;

use App\Models\TramitadorApiOperacion;
use App\Models\TramitadorApiParametro;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
    /**
     * Lee la respuesta HTTP de una operación y devuelve status, message y los
     * parámetros de salida activos extraídos de data según su ruta_json.
     *
     * @return array{status: mixed, message: mixed, data: array<string, mixed>}
     */
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

        $salida = $operacion->parametros->where('direccion', 'salida');
        $rutas = $salida->mapWithKeys(fn ($p): array => [$p->nombre => $this->rutaRelativa($p)])->all();

        foreach ($salida as $parametro) {
            $ruta = $rutas[$parametro->nombre];

            // Los campos de una lista se devuelven dentro de sus filas, no sueltos.
            if ($this->esHijoDeLista($ruta, $rutas, $salida)) {
                continue;
            }

            $hijos = $salida->filter(fn ($h): bool => str_starts_with($rutas[$h->nombre], $ruta . '.'));
            $valor = $this->extraer($data, explode('.', $ruta), $parametro->valor_por_defecto);

            $resultado['data'][$parametro->nombre] = $hijos->isNotEmpty() && is_array($valor)
                ? $this->estructurar($valor, $hijos, $rutas, $ruta)
                : $valor;
        }

        return $resultado;
    }

    /** ruta_json es relativa a data; se tolera el prefijo antiguo "data.". */
    private function rutaRelativa(TramitadorApiParametro $parametro): string
    {
        $ruta = $parametro->ruta_json ?: $parametro->nombre;

        return str_starts_with($ruta, 'data.') ? substr($ruta, 5) : $ruta;
    }

    /**
     * Un parámetro es hijo si otro parámetro de tipo object tiene su ruta como prefijo.
     *
     * @param  array<string, string>  $rutas
     * @param  Collection<int, TramitadorApiParametro>  $salida
     */
    private function esHijoDeLista(string $ruta, array $rutas, Collection $salida): bool
    {
        return $salida->contains(
            fn ($p): bool => $p->tipo_dato === 'object' && str_starts_with($ruta, $rutas[$p->nombre] . '.'),
        );
    }

    /**
     * Una lista de objetos se devuelve como lista de filas y un objeto único como una sola fila.
     *
     * @param  array<array-key, mixed>  $valor
     * @param  Collection<int, TramitadorApiParametro>  $hijos
     * @param  array<string, string>  $rutas
     * @return array<array-key, mixed>
     */
    private function estructurar(array $valor, Collection $hijos, array $rutas, string $ruta): array
    {
        $filas = $this->filas(array_is_list($valor) ? $valor : [$valor], $hijos, $rutas, $ruta);

        return array_is_list($valor) ? $filas : ($filas[0] ?? []);
    }
    /**
     * Convierte una lista en filas con los nombres de los parámetros hijos.
     *
     * @param  array<array-key, mixed>  $lista
     * @param  Collection<int, TramitadorApiParametro>  $hijos
     * @param  array<string, string>  $rutas
     * @return list<array<string, mixed>>
     */
    private function filas(array $lista, Collection $hijos, array $rutas, string $rutaPadre): array
    {
        $filas = [];

        foreach (array_is_list($lista) ? $lista : [$lista] as $elemento) {
            $fila = [];

            foreach ($hijos as $hijo) {
                $relativa = substr($rutas[$hijo->nombre], strlen($rutaPadre) + 1);
                $fila[$hijo->nombre] = is_array($elemento)
                    ? $this->extraer($elemento, explode('.', $relativa), $hijo->valor_por_defecto)
                    : $hijo->valor_por_defecto;
            }

            $filas[] = $fila;
        }

        return $filas;
    }
    /**
     * Resuelve una ruta punteada sobre data. Si en el recorrido aparece una
     * lista (p. ej. documentos), el resto de la ruta se aplica a cada elemento
     * y se devuelve la lista de valores resultantes.
     *
     * @param  array<array-key, mixed>  $datos
     * @param  list<string>  $segmentos
     */
    private function extraer(array $datos, array $segmentos, mixed $defecto): mixed
    {
        $actual = $datos;

        foreach ($segmentos as $indice => $segmento) {
            if (array_is_list($actual) && $actual !== [] && ! ctype_digit($segmento)) {
                $resto = array_slice($segmentos, $indice);

                return array_map(
                    fn (mixed $elemento): mixed => is_array($elemento)
                        ? $this->extraer($elemento, $resto, $defecto)
                        : $defecto,
                    $actual,
                );
            }

            if (! is_array($actual) || ! array_key_exists($segmento, $actual)) {
                return $defecto;
            }

            $actual = $actual[$segmento];
        }

        return $actual;
    }
}
