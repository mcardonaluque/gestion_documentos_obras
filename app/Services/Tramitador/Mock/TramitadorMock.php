<?php

declare(strict_types=1);

namespace App\Services\Tramitador\Mock;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

/**
 * Registra respuestas simuladas del tramitador con Http::fake().
 *
 * Al interceptar a nivel de cliente HTTP, TramitadorApiClient y
 * TramitadorApiOperationService funcionan sin cambios. Cada operación del
 * catálogo se declara en {@see self::OPERACIONES} y sus respuestas viven en
 * resources/mock/tramitador/{CODIGO}/{id}.json. Si el JSON tiene un "status"
 * >= 400 se devuelve como código HTTP.
 */
final class TramitadorMock
{
    /**
     * Operaciones simuladas: código => método HTTP y patrón de ruta. El último
     * grupo de captura es el id usado para elegir el fichero JSON.
     */
    private const OPERACIONES = [
        'ODOCSEXP' => ['metodo' => 'GET', 'patron' => '#^/api/v1/[^/]+/exp/(\d+)/docs/?$#'],
        'INFEXP' => ['metodo' => 'GET', 'patron' => '#^/api/v1/[^/]+/exp/(\d+)/?$#'],
        'LEXP' => ['metodo' => 'GET', 'patron' => '#^/api/v1/[^/]+/exp/?$#'],
        'OBDOC' => ['metodo' => 'GET', 'patron' => '#^/api/v1/[^/]+/doc/([^/]+)/?$#'],
        'INFROF' => ['metodo' => 'GET', 'patron' => '#^/api/v1/[^/]+/exp/\d+/rof/(\d+)/?$#'],
        'INFNIMB' => ['metodo' => 'GET', 'patron' => '#^/api/v1/[^/]+/exp/\d+/nimba/(\d+)/?$#'],
        'MODEXP' => ['metodo' => 'PUT', 'patron' => '#^/api/v1/[^/]+/exp/(\d+)/?$#'],
        'MODESEXP' => ['metodo' => 'POST', 'patron' => '#^/api/v1/[^/]+/exp/(\d+)/estado/?$#'],
        'NOTEXP' => ['metodo' => 'POST', 'patron' => '#^/api/v1/[^/]+/exp/(\d+)/notificacion/?$#'],
        'INFNOT' => ['metodo' => 'GET', 'patron' => '#^/api/v1/[^/]+/exp/\d+/notificacion/(\d+)/?$#'],
        'CREAEXP' => ['metodo' => 'POST', 'patron' => '#^/api/v1/[^/]+/exp/?$#'],
        'ICDEXP' => ['metodo' => 'POST', 'patron' => '#^/api/v1/[^/]+/exp/(\d+)/csv/?$#'],
        'NIMEXP' => ['metodo' => 'POST', 'patron' => '#^/api/v1/[^/]+/exp/(\d+)/nimb/?$#'],
        'ROFEXP' => ['metodo' => 'POST', 'patron' => '#^/api/v1/[^/]+/exp/(\d+)/rof/?$#'],
    ];

    /**
     * Activa el mock (lo usan el ServiceProvider y los tests).
     */
    public static function register(): void
    {
        Http::fake(self::rutas());
    }

    /**
     * Mapa patrón de URL => manejador.
     *
     * @return array<string, callable(Request): PromiseInterface>
     */
    public static function rutas(): array
    {
        $base = rtrim((string) config('tramitador.base_url'), '/');

        return [
            $base.'/login' => static fn (): PromiseInterface => Http::response([
                'token' => (string) config('tramitador.mock.token'),
            ]),
            $base.'/*' => static fn (Request $request): PromiseInterface => self::despachar($request),
        ];
    }

    /**
     * Elige la operación por ruta y método (varias comparten ruta, p. ej.
     * INFEXP y MODEXP). Si la ruta existe pero con otro método, responde 405.
     */
    private static function despachar(Request $request): PromiseInterface
    {
        $ruta = (string) parse_url($request->url(), PHP_URL_PATH);
        $respuesta = Http::response(['error' => 'Ruta no simulada'], 404);
        $otroMetodo = null;

        foreach (self::OPERACIONES as $codigo => $operacion) {
            if (preg_match($operacion['patron'], $ruta, $partes) !== 1) {
                continue;
            }

            $id = isset($partes[1]) ? rawurldecode($partes[1]) : 'default';

            if ($operacion['metodo'] === $request->method()) {
                $respuesta = self::responder($request, $codigo, $id);
                $otroMetodo = null;
                break;
            }

            $otroMetodo ??= Http::response(['error' => 'Método no permitido'], 405);
            $respuesta = $otroMetodo;
        }

        return $respuesta;
    }

    private static function responder(Request $request, string $codigo, string $id): PromiseInterface
    {
        parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

        /** @var array<string, mixed> $body */
        $body = $request->data();

        $cuerpo = match ($codigo) {
            'MODEXP' => ModificacionExpedienteMock::responder($id, $body),
            'MODESEXP' => ModificacionEstadoExpedienteMock::responder($id, (string) ($body['estado'] ?? '')),
            'NOTEXP' => NotificacionExpedienteMock::responder($id),
            'CREAEXP' => CreacionExpedienteMock::responder($body),
            'ICDEXP', 'NIMEXP', 'ROFEXP' => PeticionExpedienteMock::responder($codigo, $id),
            'LEXP' => ListadoExpedientesMock::aplicar(TramitadorMockFixtures::respuesta($codigo, $id), $query),
            'OBDOC' => DocumentoMock::aplicar(TramitadorMockFixtures::respuesta($codigo, $id), $query),
            default => TramitadorMockFixtures::respuesta($codigo, $id),
        };
        $estado = is_int($cuerpo['status'] ?? null) ? $cuerpo['status'] : 200;

        return str_starts_with($request->header('Authorization')[0] ?? '', 'Bearer')
            ? Http::response($cuerpo, $estado)
            : Http::response(['error' => 'No autorizado'], 401);
    }
}
