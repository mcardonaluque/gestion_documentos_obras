<?php

namespace App\Services\Tramitador;

use RuntimeException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TramitadorAuthService
{
    private const CACHE_KEY = 'tramitador_api_token';

    public static function getToken(): string
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(50), function () {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('tramite.api_token_fijo'),
            ])->get(config('tramite.base_url') . '/login');

            if (!$response->successful()) {
                throw new RuntimeException('Error autenticando con el tramitador');
            }

            return $response->json('token');
        });
    }

    public static function clearToken(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
