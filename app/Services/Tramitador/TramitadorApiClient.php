<?php

namespace App\Services\Tramitador;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Cliente HTTP de bajo nivel del tramitador.
 *
 * No conoce el catálogo de operaciones ni sus parámetros. La composición y
 * validación de una llamada definida en Administración corresponde a
 * TramitadorApiOperationService.
 */
class TramitadorApiClient
{
    protected static function client()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . TramitadorAuthService::getToken(),
            'Accept' => 'application/json',
        ]);
    }

    protected static function url(string $path): string
    {
        return rtrim(config('tramite.base_url'), '/') . '/' . ltrim($path, '/');
    }

    public static function get(string $path): Response
    {
        return self::client()->get(self::url($path));
    }

    public static function post(string $path, array $data = []): Response
    {
        return self::client()->post(self::url($path), $data);
    }

    public static function postFile(string $path, string $filePath, string $field = 'file'): Response
    {
        return self::client()
            ->attach($field, file_get_contents($filePath), basename($filePath))
            ->post(self::url($path));
    }

    public static function request(
        string $method,
        string $path,
        array $query = [],
        array $data = [],
        string $contentType = 'json',
        ?int $timeout = null,
    ): Response {
        // La operación del catálogo decide el método, formato y tiempo máximo.
        $client = self::client()->when(
            $timeout !== null,
            fn ($request) => $request->timeout($timeout),
        );

        if ($contentType === 'form') {
            $client = $client->asForm();
        }

        return $client->send(strtoupper($method), self::url($path), [
            'query' => $query,
            $contentType === 'json' ? 'json' : 'body' => $data,
        ]);
    }
}
