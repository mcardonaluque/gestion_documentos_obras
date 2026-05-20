<?php

use App\Http\Controllers\DocumentoPdfController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

// Ruta protegida para previsualizar el PDF de un documento del expediente.
// Acepta tanto enlaces externos como rutas físicas resolubles en el servidor.
Route::middleware('auth')->get('/documentos-expediente/{documento}/pdf', [DocumentoPdfController::class, 'show'])
    ->name('documentos.pdf.preview');

Route::middleware('auth')->get('/documentos-expediente/{documento}/pdf/download', [DocumentoPdfController::class, 'download'])
    ->name('documentos.pdf.download');

Route::get('/check-filament-notifications', function() {
    $user = auth()->user();

    // 1. Ver notificaciones del modelo estándar
    $standardNotifications = $user->notifications;

    // 2. Ver notificaciones de tu modelo personalizado
    $customNotifications = \App\Models\CustomNotification::where('notifiable_id', $user->id)
        ->orWhere('notifiable_id', $user->id)
        ->get();

    // 3. Ver estructura de datos
    $sampleStandard = $standardNotifications->first();
    $sampleCustom = $customNotifications->first();

    return [
        'user_id' => $user->id,
        'standard_count' => $standardNotifications->count(),
        'custom_count' => $customNotifications->count(),
        'standard_sample' => $sampleStandard ? [
            'id' => $sampleStandard->id,
            'type' => $sampleStandard->type,
            'data' => $sampleStandard->data,
            'read_at' => $sampleStandard->read_at,
        ] : null,
        'custom_sample' => $sampleCustom ? [
            'id' => $sampleCustom->id,
            'type' => $sampleCustom->type,
            'data' => $sampleCustom->data,
            'read_at' => $sampleCustom->read_at,
            //'is_read' => $sampleCustom->is_read,
        ] : null,
    ];
});

// Temporary runtime diagnostics for SQLSRV/PDO options (remove after use).
$sqlsrvRuntimeDiagHandler = function () {
    $token = (string) request()->query('token', '');
    $expectedToken = (string) env('DIAG_TOKEN', '');

    // If a token is configured, require it using a timing-safe compare.
    if ($expectedToken !== '' && ! hash_equals($expectedToken, $token)) {
        abort(403);
    }

    // If no token is configured, restrict access to localhost/private IPs.
    if ($expectedToken === '') {
        $ip = request()->ip() ?? '';
        $isLocal = in_array($ip, ['127.0.0.1', '::1'], true)
            || Str::startsWith($ip, '10.')
            || Str::startsWith($ip, '192.168.')
            || preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $ip);

        if (! $isLocal) {
            abort(403);
        }
    }

    return response()->json([
        'sapi' => php_sapi_name(),
        'php_version' => PHP_VERSION,
        'php_ini' => php_ini_loaded_file(),
        'loaded_extensions' => [
            'sqlsrv' => phpversion('sqlsrv') ?: null,
            'pdo_sqlsrv' => phpversion('pdo_sqlsrv') ?: null,
        ],
        'pdo_constants' => [
            'SQLSRV_ATTR_QUERY_TIMEOUT' => defined('PDO::SQLSRV_ATTR_QUERY_TIMEOUT') ? \PDO::SQLSRV_ATTR_QUERY_TIMEOUT : null,
            'ATTR_STRINGIFY_FETCHES' => \PDO::ATTR_STRINGIFY_FETCHES,
            'ATTR_ERRMODE' => \PDO::ATTR_ERRMODE,
            'ERRMODE_EXCEPTION' => \PDO::ERRMODE_EXCEPTION,
        ],
        'laravel_config' => [
            'default_connection' => config('database.default'),
            'obras_driver' => config('database.connections.Obras.driver'),
            'obras_options' => config('database.connections.Obras.options'),
            'obras_login_timeout' => config('database.connections.Obras.login_timeout'),
        ],
        'request' => [
            'host' => request()->getHost(),
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
        ],
        'timestamp' => now()->toIso8601String(),
    ]);
};

Route::get('/_diag/sqlsrv-runtime', $sqlsrvRuntimeDiagHandler);
Route::get('/obras/_diag/sqlsrv-runtime', $sqlsrvRuntimeDiagHandler);
