<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

/**
 * Simula las operaciones que crean una petición sobre un expediente abierto:
 * ICDEXP (CSV), NIMEXP (firma NIMBUS) y ROFEXP (firma de documento).
 *
 * El expediente debe existir (404 de INFEXP) y estar abierto (409 si no). Los
 * campos obligatorios los valida TramitadorApiOperationService antes de
 * enviar. La petición devuelta es "{prefijo}{id_exp}" y no se persiste.
 */
final class PeticionExpedienteMock
{
    /** Prefijo del id_peticion por operación. */
    private const PREFIJOS = [
        'ICDEXP' => '94',
        'NIMEXP' => '95',
        'ROFEXP' => '96',
    ];

    /**
     * Simula la creación de una petición sobre el expediente: id_peticion o 409/404.
     *
     * @return array<string, mixed>
     */
    public static function responder(string $codigo, string $idExp): array
    {
        $expediente = TramitadorMockFixtures::respuesta('INFEXP', $idExp);
        $estado = $expediente['status'] ?? null;
        $data = is_array($expediente['data'] ?? null) ? $expediente['data'] : [];
        $prefijo = self::PREFIJOS[$codigo] ?? '99';
        $resultado = [
            'status' => 'ok',
            'message' => "Petición {$codigo} creada en el expediente {$idExp}",
            'data' => ['id_peticion' => "{$prefijo}{$idExp}"],
        ];

        if (is_int($estado) && $estado >= 400) {
            $resultado = $expediente;
        } elseif (($data['estado'] ?? null) !== ModificacionExpedienteMock::ESTADO_ABIERTO) {
            $resultado = ['status' => 409, 'message' => "El expediente {$idExp} está cerrado: no admite la operación {$codigo}", 'data' => null];
        }

        return $resultado;
    }
}
