<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

use DateTimeImmutable;

/**
 * Aplica a la respuesta simulada de LEXP los filtros de la consulta:
 * ejercicio, id_uo, estado, num_exp, asunto, id_grp y rango de fecha de
 * apertura. Devuelve como máximo 100 expedientes por fecha de apertura
 * descendente, igual que la API real.
 */
final class ListadoExpedientesMock
{
    private const LIMITE = 100;

    /**
     * Filtra, ordena y limita el listado simulado según los parámetros de la consulta.
     *
     * @param  array<string, mixed>  $cuerpo  Contenido del JSON base.
     * @param  array<string, mixed>  $query  Parámetros de la petición.
     * @return array<string, mixed>
     */
    public static function aplicar(array $cuerpo, array $query): array
    {
        $data = is_array($cuerpo['data'] ?? null) ? $cuerpo['data'] : [];
        $expedientes = is_array($data['expedientes'] ?? null) ? $data['expedientes'] : [];

        $filtrados = array_values(array_filter(
            $expedientes,
            static fn (mixed $e): bool => is_array($e) && self::cumple($e, $query),
        ));

        usort($filtrados, static fn (array $a, array $b): int => self::fecha($b['fecha_apertura'] ?? null) <=> self::fecha($a['fecha_apertura'] ?? null));
        $filtrados = array_slice($filtrados, 0, self::LIMITE);

        $cuerpo['data'] = [
            'ejercicio' => isset($query['ejercicio']) ? (int) $query['ejercicio'] : ($data['ejercicio'] ?? null),
            'id_uo' => $query['id_uo'] ?? ($data['id_uo'] ?? null),
            'total' => count($filtrados),
            'expedientes' => $filtrados,
        ];

        return $cuerpo;
    }

    /**
     * @param  array<array-key, mixed>  $e
     * @param  array<string, mixed>  $q
     */
    private static function cumple(array $e, array $q): bool
    {
        return self::igual($e, $q, 'ejercicio')
            && self::igual($e, $q, 'id_uo')
            && self::igual($e, $q, 'estado')
            && self::igual($e, $q, 'num_exp')
            && self::igual($e, $q, 'id_grp')
            && self::contiene($e, $q)
            && self::enRango($e, $q);
    }

    /**
     * @param  array<array-key, mixed>  $e
     * @param  array<string, mixed>  $q
     */
    private static function igual(array $e, array $q, string $campo): bool
    {
        return ! isset($q[$campo]) || (string) ($e[$campo] ?? '') === (string) $q[$campo];
    }

    /**
     * @param  array<array-key, mixed>  $e
     * @param  array<string, mixed>  $q
     */
    private static function contiene(array $e, array $q): bool
    {
        return ! isset($q['asunto'])
            || stripos((string) ($e['asunto'] ?? ''), (string) $q['asunto']) !== false;
    }

    /**
     * @param  array<array-key, mixed>  $e
     * @param  array<string, mixed>  $q
     */
    private static function enRango(array $e, array $q): bool
    {
        $fecha = self::fecha($e['fecha_apertura'] ?? null);
        $desde = self::fecha($q['fecha_apertrura_desde'] ?? $q['fecha_apertura_desde'] ?? null);
        $hasta = self::fecha($q['fecha_apertura_hasta'] ?? null);

        return ($desde === 0 || $fecha >= $desde) && ($hasta === 0 || $fecha <= $hasta);
    }

    private static function fecha(mixed $valor): int
    {
        $fecha = is_string($valor) ? DateTimeImmutable::createFromFormat('!d/m/Y', $valor) : false;

        return $fecha === false ? 0 : $fecha->getTimestamp();
    }
}
