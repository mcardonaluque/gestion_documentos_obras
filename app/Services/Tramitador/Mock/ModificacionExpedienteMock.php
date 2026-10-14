<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

/**
 * Simula MODEXP (PUT /{dir3_emisor}/exp/{id_exp}).
 *
 * Parte del expediente de INFEXP y le aplica los campos enviados en el body.
 * Reproduce tres resultados: modificación correcta (200), expediente cerrado
 * (409) y duración sin meses cuando el tipo la exige (422). Si el expediente no
 * existe devuelve el 404 de INFEXP. No persiste nada.
 */
final class ModificacionExpedienteMock
{
    /** Campos que MODEXP admite modificar (los demás del body se ignoran). */
    private const MODIFICABLES = [
        'asunto', 'fecha_apertura', 'fecha_inicio_plazo', 'num_registro', 'fecha_registro',
        'id_grp', 'id_usu_responsable', 'id_tipo_duracion', 'duracion_meses',
        'id_efecto_silencio', 'num_exp_antiguo', 'id_cla_fun_especificas',
    ];

    /** Solo los expedientes abiertos admiten cambios y notificaciones. */
    public const ESTADO_ABIERTO = 'ABI';

    /** Tipos de duración que obligan a indicar duracion_meses. */
    private const DURACION_CON_MESES = ['SP', 'NR'];

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public static function responder(string $idExp, array $body): array
    {
        $expediente = TramitadorMockFixtures::respuesta('INFEXP', $idExp);
        $data = is_array($expediente['data'] ?? null) ? $expediente['data'] : [];
        $cambios = array_intersect_key($body, array_flip(self::MODIFICABLES));
        $resultado = $expediente;

        if (is_int($expediente['status'] ?? null) && $expediente['status'] >= 400) {
            $resultado = $expediente;
        } elseif (($data['estado'] ?? null) !== self::ESTADO_ABIERTO) {
            $resultado = self::error(409, "El expediente {$idExp} está cerrado y no puede modificarse");
        } elseif (self::faltaDuracion($data, $cambios)) {
            $resultado = self::error(422, 'duracion_meses es obligatorio para el tipo de duración indicado');
        } else {
            $resultado = [
                'status' => 'ok',
                'message' => "Se modifica el expediente con identificador {$idExp}",
                'data' => array_merge($data, $cambios),
            ];
        }

        return $resultado;
    }

    /**
     * @param  array<array-key, mixed>  $data
     * @param  array<array-key, mixed>  $cambios
     */
    private static function faltaDuracion(array $data, array $cambios): bool
    {
        $tipo = $cambios['id_tipo_duracion'] ?? $data['id_tipo_duracion'] ?? null;
        $meses = $cambios['duracion_meses'] ?? null;

        return in_array($tipo, self::DURACION_CON_MESES, true)
            && array_key_exists('id_tipo_duracion', $cambios)
            && ($meses === null || $meses === '');
    }

    /**
     * @return array<string, mixed>
     */
    private static function error(int $codigo, string $mensaje): array
    {
        return ['status' => $codigo, 'message' => $mensaje, 'data' => null];
    }
}
