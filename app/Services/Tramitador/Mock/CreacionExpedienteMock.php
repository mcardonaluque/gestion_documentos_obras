<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

/**
 * Simula CREAEXP (POST /{dir3_emisor}/exp).
 *
 * Devuelve el expediente de resources/mock/tramitador/CREAEXP/default.json
 * reflejando el ref_emisor recibido. La validación de campos obligatorios,
 * origen de catálogos y duración la hace TramitadorApiOperationService. No
 * persiste nada: cada llamada devuelve el mismo id_exp.
 */
final class CreacionExpedienteMock
{
    /**
     * Devuelve el expediente base reflejando el ref_emisor recibido en el body.
     *
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public static function responder(array $body): array
    {
        $respuesta = TramitadorMockFixtures::respuesta('CREAEXP', 'default');

        if (isset($respuesta['data']) && is_array($respuesta['data']) && isset($body['ref_emisor'])) {
            $respuesta['data']['ref_emisor'] = $body['ref_emisor'];
        }

        return $respuesta;
    }
}
