<?php

declare(strict_types=1);

namespace App\Services\Tramitador;

use App\Models\TramitadorApiOperacion;
use App\Models\TramitadorApiParametro;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

/**
 * Ejecuta una función del tramitador configurada en tramitador_api_operaciones.
 *
 * Los parámetros activos de entrada se leen de tramitador_api_parametros: los
 * de ubicación path sustituyen marcadores de la ruta, query forman la consulta
 * y body forman el contenido de la petición.
 */
class TramitadorApiOperationService
{
    public function __construct(private readonly TramitadorReglasCatalogo $reglasCatalogo)
    {
    }

    /**
     * Ejecuta la operación activa identificada por su código, validando los
     * valores de entrada y lanzando la petición mediante TramitadorApiClient.
     *
     * @param array<string, mixed> $valores Valores de los parámetros de entrada.
     */
    public function ejecutar(string $codigo, array $valores = []): Response
    {
        // Solo se pueden invocar las operaciones activas administradas en el portal.
        $operacion = TramitadorApiOperacion::query()
            ->where('codigo', $codigo)
            ->where('activa', true)
            ->with(['parametros' => fn ($query) => $query->where('activo', true)->orderBy('orden')])
            ->first();

        if ($operacion === null) {
            throw new InvalidArgumentException("La función API activa [{$codigo}] no existe.");
        }

        $parametrosEntrada = $operacion->parametros
            ->where('direccion', 'entrada');

        $this->validar($parametrosEntrada, $valores);

        $ruta = $this->componerRuta($operacion->ruta, $parametrosEntrada, $valores);
        $query = $this->valoresEnUbicacion($parametrosEntrada, $valores, 'query');
        $body = $this->valoresEnUbicacion($parametrosEntrada, $valores, 'body');

        return TramitadorApiClient::request(
            $operacion->metodo_http,
            $ruta,
            $query,
            $body,
            $operacion->tipo_contenido,
            $operacion->timeout_segundos,
        );
    }

    /** @param Collection<int, TramitadorApiParametro> $parametros */
    private function validar(Collection $parametros, array $valores): void
    {
        $reglas = [];

        foreach ($parametros->reject(fn (TramitadorApiParametro $p): bool => $this->esContenedor($p)) as $parametro) {
            $reglas[$parametro->nombre] = array_filter([
                $parametro->obligatorio ? 'required' : 'nullable',
                ...$this->reglasCatalogo->reglas($parametro->reglas_validacion, $valores),
            ]);
        }

        Validator::make($valores, $reglas)->validate();
    }

    /** @param Collection<int, TramitadorApiParametro> $parametros */
    private function componerRuta(string $ruta, Collection $parametros, array $valores): string
    {
        // Los valores se codifican para que no alteren la estructura de la URL.
        foreach ($parametros->where('ubicacion', 'path') as $parametro) {
            $nombre = $parametro->nombre;
            $valor = $valores[$nombre] ?? $parametro->valor_por_defecto;

            if ($valor === null || $valor === '') {
                throw ValidationException::withMessages([
                    $nombre => "El parámetro de ruta [{$nombre}] es obligatorio.",
                ]);
            }

            $ruta = str_replace("{{$nombre}}", rawurlencode((string) $valor), $ruta);
        }

        if (preg_match('/\{[^}]+}/', $ruta) === 1) {
            throw new InvalidArgumentException("La ruta contiene parámetros sin definir: {$ruta}");
        }

        return $ruta;
    }

    /** @param Collection<int, TramitadorApiParametro> $parametros */
    private function valoresEnUbicacion(Collection $parametros, array $valores, string $ubicacion): array
    {
        $resultado = [];

        foreach ($parametros->where('ubicacion', $ubicacion) as $parametro) {
            $valor = $valores[$parametro->nombre] ?? $parametro->valor_por_defecto;

            if ($valor !== null && ! $this->esContenedor($parametro)) {
                Arr::set($resultado, $this->clave($parametro, $ubicacion), $this->normalizar($parametro, $valor, $ubicacion));
            }
        }

        return $resultado;
    }

    /** Los objetos agrupan a sus hijos: no se validan ni se envían por sí mismos. */
    private function esContenedor(TramitadorApiParametro $parametro): bool
    {
        return $parametro->tipo_dato === 'object';
    }

    /** En el body la clave sigue la ruta del catálogo (p. ej. documento.nombre) para anidar el JSON. */
    private function clave(TramitadorApiParametro $parametro, string $ubicacion): string
    {
        $ruta = (string) $parametro->ruta_json;

        return $ubicacion === 'body' && str_contains($ruta, '.') ? $ruta : $parametro->nombre;
    }

    /**
     * En la query un booleano debe viajar como "true"/"false" (http_build_query
     * lo enviaría como 1/0). En el body se conserva el booleano JSON.
     */
    private function normalizar(TramitadorApiParametro $parametro, mixed $valor, string $ubicacion): mixed
    {
        if ($parametro->tipo_dato !== 'boolean') {
            return $valor;
        }

        $booleano = filter_var($valor, FILTER_VALIDATE_BOOLEAN);

        return $ubicacion === 'query' ? ($booleano ? 'true' : 'false') : $booleano;
    }
}
