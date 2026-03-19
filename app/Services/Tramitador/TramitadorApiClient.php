<?php

namespace App\Services\Tramitador;

use Illuminate\Support\Facades\Http;

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

    public static function get(string $path)
    {
        return self::client()->get(self::url($path));
    }

    public static function post(string $path, array $data = [])
    {
        return self::client()->post(self::url($path), $data);
    }

    public static function postFile(string $path, string $filePath, string $field = 'file')
    {
        return self::client()
            ->attach($field, file_get_contents($filePath), basename($filePath))
            ->post(self::url($path));
    }
}
