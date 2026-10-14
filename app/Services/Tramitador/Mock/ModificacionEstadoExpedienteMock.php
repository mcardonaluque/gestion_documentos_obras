<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

use RuntimeException;

/**
 * Simula MODESEXP: solo cierra expedientes abiertos sin trámites pendientes.
 *
 * El resultado no se persiste, siguiendo el comportamiento de los demás mocks.
 */
final class ModificacionEstadoExpedienteMock
{
    /** @var list<string> */
    private const ESTADOS_PENDIENTES = ['PEN', 'PTE_FIRM', 'EN_PROCESO', 'PTE_VAL', 'VAL_SOP'];

    /**
     * @return array<string, mixed>
     */
    public static function responder(string $idExp, string $estado): array
    {
        $expediente = TramitadorMockFixtures::respuesta('INFEXP', $idExp);
        $data = is_array($expediente['data'] ?? null) ? $expediente['data'] : [];

        if (is_int($expediente['status'] ?? null) && $expediente['status'] >= 400) {
            return $expediente;
        }

        if (($data['estado'] ?? null) !== ModificacionExpedienteMock::ESTADO_ABIERTO) {
            return self::error(409, "El expediente {$idExp} no está abierto");
        }

        if (self::tieneTramitesPendientes($idExp)) {
            return self::error(409, "El expediente {$idExp} tiene trámites pendientes de finalizar");
        }

        return [
            'status' => 'ok',
            'message' => "Se modifica el estado del expediente {$idExp}",
            'data' => [
                'id_exp' => (int) $idExp,
                'estado' => $estado,
            ],
        ];
    }

    private static function tieneTramitesPendientes(string $idExp): bool
    {
        foreach (['INFNIMB', 'INFROF', 'INFNOT'] as $operacion) {
            foreach (glob(resource_path("mock/tramitador/{$operacion}/*.json")) ?: [] as $fichero) {
                $contenido = file_get_contents($fichero);
                $respuesta = $contenido === false ? null : json_decode($contenido, true);

                if (! is_array($respuesta)) {
                    throw new RuntimeException("Fixture inválido al comprobar trámites pendientes: {$fichero}");
                }

                if (is_int($respuesta['status'] ?? null) && $respuesta['status'] >= 400) {
                    continue;
                }

                if (! is_array($respuesta['data'] ?? null)) {
                    throw new RuntimeException("Fixture inválido al comprobar trámites pendientes: {$fichero}");
                }

                $data = $respuesta['data'];
                if ((string) ($data['id_exp'] ?? '') !== $idExp) {
                    continue;
                }

                if (self::estadoPendiente($operacion, $data)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function estadoPendiente(string $operacion, array $data): bool
    {
        $estado = $data['estado'] ?? null;
        $notificacionPendiente = $operacion === 'INFNOT'
            && ($data['estado_notificacion'] ?? null) === 'Pendiente de Comparecencia';

        return $notificacionPendiente
            || (is_string($estado) && in_array($estado, self::ESTADOS_PENDIENTES, true));
    }

    /**
     * @return array<string, mixed>
     */
    private static function error(int $codigo, string $mensaje): array
    {
        return ['status' => $codigo, 'message' => $mensaje, 'data' => null];
    }
}
