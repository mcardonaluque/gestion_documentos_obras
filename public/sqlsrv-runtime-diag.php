<?php

declare(strict_types=1);

$token = isset($_GET['token']) ? (string) $_GET['token'] : '';

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$expectedToken = (string) env('DIAG_TOKEN', '');

if ($expectedToken !== '' && ! hash_equals($expectedToken, $token)) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Forbidden'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($expectedToken === '') {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $isLocal = in_array($ip, ['127.0.0.1', '::1'], true)
        || str_starts_with($ip, '10.')
        || str_starts_with($ip, '192.168.')
        || preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $ip);

    if (! $isLocal) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Forbidden'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

header('Content-Type: application/json; charset=utf-8');

$dbProbe = [];

try {
    $connection = app('db')->connection('Obras');
    $pdo = $connection->getPdo();

    $dbProbe['connected'] = true;
    $dbProbe['pdo_class'] = get_class($pdo);
    $dbProbe['query_timeout_attr'] = defined('PDO::SQLSRV_ATTR_QUERY_TIMEOUT')
        ? $pdo->getAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT)
        : null;

    $dbProbe['roles_top_1'] = $connection
        ->table('roles')
        ->select('name')
        ->limit(1)
        ->first();
} catch (Throwable $exception) {
    $dbProbe['connected'] = false;
    $dbProbe['error'] = [
        'class' => get_class($exception),
        'message' => $exception->getMessage(),
    ];
}

echo json_encode([
    'sapi' => php_sapi_name(),
    'php_version' => PHP_VERSION,
    'php_ini' => php_ini_loaded_file(),
    'loaded_extensions' => [
        'sqlsrv' => phpversion('sqlsrv') ?: null,
        'pdo_sqlsrv' => phpversion('pdo_sqlsrv') ?: null,
    ],
    'pdo_constants' => [
        'SQLSRV_ATTR_QUERY_TIMEOUT' => defined('PDO::SQLSRV_ATTR_QUERY_TIMEOUT') ? PDO::SQLSRV_ATTR_QUERY_TIMEOUT : null,
        'ATTR_STRINGIFY_FETCHES' => PDO::ATTR_STRINGIFY_FETCHES,
        'ATTR_ERRMODE' => PDO::ATTR_ERRMODE,
        'ERRMODE_EXCEPTION' => PDO::ERRMODE_EXCEPTION,
    ],
    'laravel_config' => [
        'default_connection' => config('database.default'),
        'obras_driver' => config('database.connections.Obras.driver'),
        'obras_options' => config('database.connections.Obras.options'),
        'obras_login_timeout' => config('database.connections.Obras.login_timeout'),
    ],
    'db_probe' => $dbProbe,
    'request' => [
        'host' => $_SERVER['HTTP_HOST'] ?? null,
        'server_name' => $_SERVER['SERVER_NAME'] ?? null,
        'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? null,
        'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
        'script_name' => $_SERVER['SCRIPT_NAME'] ?? null,
        'script_filename' => $_SERVER['SCRIPT_FILENAME'] ?? null,
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? null,
    ],
    'server' => [
        'hostname' => gethostname() ?: null,
        'php_sapi' => PHP_SAPI,
    ],
    'timestamp' => now()->toIso8601String(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
