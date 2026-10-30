<?php

namespace App\Services\Tramitador;

use RuntimeException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Servicio de autenticación contra la API del tramitador.
 *
 * Canjea el token fijo de config/tramitador.php por un token de sesión en /login
 * y lo cachea 50 minutos. Lo usa TramitadorApiClient para firmar cada petición.
 */
class TramitadorAuthService
{
    private const CACHE_KEY = 'tramitador_api_token';

    /**
     * Devuelve el token de sesión vigente, renovándolo si ha caducado en caché.
     */
    public static function getToken(): string
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(50), function () {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('tramitador.api_token_fijo'),
            ])->get(config('tramitador.base_url') . '/login');

            if (!$response->successful()) {
                throw new RuntimeException('Error autenticando con el tramitador');
            }

            return $response->json('token');
        });
    }

    /**
     * Invalida el token cacheado para forzar una nueva autenticación.
     */
    public static function clearToken(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
