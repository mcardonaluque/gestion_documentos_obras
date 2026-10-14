<?php
return [
    'base_url' => env('TRAMITADOR_BASE_URL', 'https://api-se.diputacion.malaga.es/api/v1'),
    'api_token_fijo' => env('TRAMITADOR_API_TOKEN'),
    'dir3_emisor' => env('TRAMITADOR_DIR3'),

    'mock' => [
        // Si es true, las llamadas HTTP al tramitador se interceptan con Http::fake().
        'enabled' => (bool) env('TRAMITADOR_MOCK', false),
        'token' => 'mock-token-tramitador',
    ],
];
