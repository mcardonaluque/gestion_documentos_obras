<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

/**
 * Simula NOTEXP (POST /{dir3_emisor}/exp/{id_exp}/notificacion).
 *
 * Solo se puede crear una notificación en un expediente abierto (409 si no) y
 * el expediente debe existir (404 de INFEXP). El tipo de envío (NOT/COM), los
 * campos obligatorios y fecha_envio_prog cuando programar_envio es true los
 * valida TramitadorApiOperationService antes de enviar. No persiste nada.
 */
final class NotificacionExpedienteMock
{
    /**
     * @return array<string, mixed>
     */
    public static function responder(string $idExp): array
    {
        $expediente = TramitadorMockFixtures::respuesta('INFEXP', $idExp);
        $estado = $expediente['status'] ?? null;
        $data = is_array($expediente['data'] ?? null) ? $expediente['data'] : [];
        $resultado = [
            'status' => 'ok',
            'message' => "Se crea la notificación en el expediente {$idExp}",
            'data' => ['id_peticion' => "93{$idExp}"],
        ];

        if (is_int($estado) && $estado >= 400) {
            $resultado = $expediente;
        } elseif (($data['estado'] ?? null) !== ModificacionExpedienteMock::ESTADO_ABIERTO) {
            $resultado = ['status' => 409, 'message' => "El expediente {$idExp} está cerrado: no admite notificaciones", 'data' => null];
        }

        return $resultado;
    }
}
