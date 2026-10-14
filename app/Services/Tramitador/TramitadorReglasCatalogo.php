<?php

declare(strict_types=1);

namespace App\Services\Tramitador;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Traduce la columna reglas_validacion del catálogo a reglas de Laravel.
 *
 * Admite dos formatos:
 *  - Texto con reglas Laravel separadas por "|" (p. ej. "integer|min:1").
 *  - JSON declarativo con las claves: tipo, valores, origen, requerido_si,
 *    rango_si, base64 y max_bytes. Un JSON mal formado se ignora.
 *
 * El JSON nunca indica tablas ni columnas: "origen" es una clave de
 * {@see self::ORIGENES_PERMITIDOS}, así que el catálogo no puede apuntar a
 * datos arbitrarios.
 */
class TramitadorReglasCatalogo
{
    /** Correspondencia entre "tipo" del catálogo y regla Laravel. */
    private const TIPOS = [
        'integer' => 'integer',
        'string' => 'string',
        'number' => 'numeric',
        'boolean' => 'boolean',
        'date' => 'date_format:d/m/Y',
    ];

    /**
     * Fuentes de catálogo autorizadas para la clave "origen".
     *
     * @var array<string, array{conexion: string, tabla: string, columna: string}>
     */
    private const ORIGENES_PERMITIDOS = [
        'duracion_procedimiento' => ['conexion' => 'Obras', 'tabla' => 'catalogo_duracion_procedimiento', 'columna' => 'identificador'],
        'efectos_silencio' => ['conexion' => 'Obras', 'tabla' => 'catalogo_efectos_silencio', 'columna' => 'identificador'],
        'estados_elaboracion_documento' => ['conexion' => 'Obras', 'tabla' => 'catalogo_estados_elaboracion_documento', 'columna' => 'identificador'],
        'funciones_comunes' => ['conexion' => 'Obras', 'tabla' => 'catalogo_funciones_comunes', 'columna' => 'IDENTIFICADOR'],
        'funciones_especificas' => ['conexion' => 'Obras', 'tabla' => 'catalogo_funciones_especificas', 'columna' => 'id_fun_especifica'],
        'tipos_documentales' => ['conexion' => 'Obras', 'tabla' => 'catalogo_tipos_documentales', 'columna' => 'identificador'],
        'tipo_firmas' => ['conexion' => 'Obras', 'tabla' => 'catalogo_tipo_firmas', 'columna' => 'identificador'],
    ];

    /**
     * @param  array<string, mixed>  $valores  Todos los valores de la petición (las reglas condicionales los consultan).
     * @return list<mixed>
     */
    public function reglas(?string $definicion, array $valores): array
    {
        $texto = trim((string) $definicion);

        return match (true) {
            $texto === '' => [],
            str_starts_with($texto, '{') => $this->reglasJson($texto, $valores),
            default => explode('|', $texto),
        };
    }

    /**
     * @param  array<string, mixed>  $valores
     * @return list<mixed>
     */
    private function reglasJson(string $json, array $valores): array
    {
        $definicion = json_decode($json, true);

        return is_array($definicion) ? array_merge(
            $this->tipo($definicion['tipo'] ?? null),
            $this->valoresPermitidos($definicion['valores'] ?? null),
            $this->origen($definicion['origen'] ?? null),
            $this->requeridoSi($definicion['requerido_si'] ?? null, $valores),
            $this->rangoSi($definicion['rango_si'] ?? null, $valores),
            $this->base64($definicion),
        ) : [];
    }

    /** @return list<string> */
    private function tipo(mixed $tipo): array
    {
        return is_string($tipo) && isset(self::TIPOS[$tipo]) ? [self::TIPOS[$tipo]] : [];
    }

    /**
     * Lista cerrada de valores admitidos.
     *
     * @return list<mixed>
     */
    private function valoresPermitidos(mixed $valores): array
    {
        return is_array($valores) && $valores !== [] ? [Rule::in($valores)] : [];
    }

    /**
     * El valor debe existir en el catálogo indicado por la clave "origen".
     *
     * @return list<Closure>
     */
    private function origen(mixed $origen): array
    {
        $fuente = is_string($origen) ? (self::ORIGENES_PERMITIDOS[$origen] ?? null) : null;

        return $fuente === null ? [] : [
            static function (string $atributo, mixed $valor, Closure $fail) use ($fuente, $origen): void {
                $existe = DB::connection($fuente['conexion'])
                    ->table($fuente['tabla'])
                    ->where($fuente['columna'], (string) $valor)
                    ->exists();

                if (! $existe) {
                    $fail("El valor de {$atributo} no existe en el catálogo {$origen}.");
                }
            },
        ];
    }

    /**
     * Obligatorio si otro parámetro toma uno de los valores indicados.
     *
     * @param  array<string, mixed>  $valores
     * @return list<mixed>
     */
    private function requeridoSi(mixed $condicion, array $valores): array
    {
        $parametro = is_array($condicion) ? ($condicion['parametro'] ?? null) : null;

        return is_array($condicion) && is_string($parametro)
            ? [Rule::requiredIf(fn (): bool => $this->coincide($valores[$parametro] ?? null, $condicion['valores'] ?? []))]
            : [];
    }

    /**
     * Rango numérico que depende del valor de otro parámetro.
     *
     * @param  array<string, mixed>  $valores
     * @return list<Closure>
     */
    private function rangoSi(mixed $rangos, array $valores): array
    {
        return is_array($rangos) ? [
            function (string $atributo, mixed $valor, Closure $fail) use ($rangos, $valores): void {
                foreach ($rangos as $rango) {
                    $this->comprobarRango($atributo, $valor, $rango, $valores, $fail);
                }
            },
        ] : [];
    }

    /**
     * @param  array<string, mixed>  $valores
     */
    private function comprobarRango(string $atributo, mixed $valor, mixed $rango, array $valores, Closure $fail): void
    {
        $aplica = is_array($rango)
            && is_string($rango['parametro'] ?? null)
            && $this->coincide($valores[$rango['parametro']] ?? null, $rango['valor'] ?? null)
            && is_numeric($valor);

        if ($aplica && ($valor < ($rango['min'] ?? PHP_INT_MIN) || $valor > ($rango['max'] ?? PHP_INT_MAX))) {
            $fail("{$atributo} debe estar entre {$rango['min']} y {$rango['max']} cuando {$rango['parametro']} es {$rango['valor']}.");
        }
    }

    /**
     * Contenido en base64 y tamaño máximo (en bytes del contenido decodificado).
     *
     * @param  array<array-key, mixed>  $definicion
     * @return list<Closure>
     */
    private function base64(array $definicion): array
    {
        $esBase64 = ($definicion['base64'] ?? false) === true;
        $maximo = is_int($definicion['max_bytes'] ?? null) ? $definicion['max_bytes'] : null;

        return $esBase64 || $maximo !== null ? [
            static function (string $atributo, mixed $valor, Closure $fail) use ($esBase64, $maximo): void {
                $contenido = $esBase64 ? base64_decode((string) $valor, true) : (string) $valor;

                if ($contenido === false) {
                    $fail("{$atributo} debe estar codificado en base64.");
                } elseif ($maximo !== null && strlen($contenido) > $maximo) {
                    $fail("{$atributo} supera el tamaño máximo de {$maximo} bytes.");
                }
            },
        ] : [];
    }

    /**
     * Compara el valor recibido con uno o varios esperados. Los booleanos
     * aceptan "true"/"false", ya que en la query viajan como texto.
     */
    private function coincide(mixed $actual, mixed $esperados): bool
    {
        $encontrado = false;

        foreach (is_array($esperados) ? $esperados : [$esperados] as $esperado) {
            $encontrado = $encontrado || (is_bool($esperado)
                ? filter_var($actual, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === $esperado
                : $actual !== null && (string) $actual === (string) $esperado);
        }

        return $encontrado;
    }
}
