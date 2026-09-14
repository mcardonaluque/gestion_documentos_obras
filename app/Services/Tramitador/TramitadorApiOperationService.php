<?php

declare(strict_types=1);

namespace App\Services\Tramitador;

use App\Models\TramitadorApiOperacion;
use App\Models\TramitadorApiParametro;
use Illuminate\Http\Client\Response;
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
    /**
     * Fuentes de catálogo autorizadas para futuras validaciones declarativas.
     *
     * La configuración almacenada debe referirse a la clave (por ejemplo,
     * "tipo_firmas"), nunca a una conexión, tabla o columna arbitrarias.
     */
    private const ORIGENES_PERMITIDOS = [
        'duracion_procedimiento' => [
            'conexion' => 'Obras',
            'tabla' => 'catalogo_duracion_procedimiento',
            'columna' => 'identificador',
        ],
        'efectos_silencio' => [
            'conexion' => 'Obras',
            'tabla' => 'catalogo_efectos_silencio',
            'columna' => 'identificador',
        ],
        'estados_elaboracion_documento' => [
            'conexion' => 'Obras',
            'tabla' => 'catalogo_estados_elaboracion_documento',
            'columna' => 'identificador',
        ],
        'funciones_comunes' => [
            'conexion' => 'Obras',
            'tabla' => 'catalogo_funciones_comunes',
            'columna' => 'IDENTIFICADOR',
        ],
        'funciones_especificas' => [
            'conexion' => 'Obras',
            'tabla' => 'catalogo_funciones_especificas',
            'columna' => 'identificador',
        ],
        'tipos_documentales' => [
            'conexion' => 'Obras',
            'tabla' => 'catalogo_tipos_documentales',
            'columna' => 'identificador',
        ],
        'tipo_firmas' => [
            'conexion' => 'Obras',
            'tabla' => 'catalogo_tipo_firmas',
            'columna' => 'identificador',
        ],
    ];

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

        foreach ($parametros as $parametro) {
            // reglas_validacion usa reglas Laravel separadas por "|".
            $reglas[$parametro->nombre] = array_filter([
                $parametro->obligatorio ? 'required' : 'nullable',
                ...($parametro->reglas_validacion === null || $parametro->reglas_validacion === ''
                    ? []
                    : explode('|', $parametro->reglas_validacion)),
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

            if ($valor !== null) {
                $resultado[$parametro->nombre] = $valor;
            }
        }

        return $resultado;
    }
}
