<?php

/*
|--------------------------------------------------------------------------
| Vercel serverless entry point
|--------------------------------------------------------------------------
|
| Serverless runtimes mount the app filesystem as read-only (except /tmp),
| so Laravel's writable cache paths must be redirected to /tmp before the
| framework boots. These are set in code because vercel.json env variables
| are not guaranteed to reach the function runtime.
|
*/

$_ENV['APP_NAME'] = "56'30 Studio Cafe";
$_SERVER['APP_NAME'] = "56'30 Studio Cafe";
$_ENV['APP_ENV'] = 'production';
$_SERVER['APP_ENV'] = 'production';
$_ENV['APP_DEBUG'] = 'true';
$_SERVER['APP_DEBUG'] = 'true';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_SERVER['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
$_SERVER['APP_EVENTS_CACHE'] = '/tmp/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_SERVER['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_SERVER['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_SERVER['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['CACHE_DRIVER'] = 'array';
$_SERVER['CACHE_DRIVER'] = 'array';
$_ENV['CACHE_STORE'] = 'array';
$_SERVER['CACHE_STORE'] = 'array';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_SERVER['SESSION_DRIVER'] = 'cookie';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp';

/*
| Mirror Vercel's database env vars into $_ENV so Laravel's env()/config()
| helpers resolve them regardless of how the platform exposes them.
*/
foreach (['PGHOST', 'PGHOST_UNPOOLED', 'PGDATABASE', 'PGUSER', 'PGPASSWORD', 'PGPORT', 'DATABASE_URL'] as $pgVar) {
    if (isset($_SERVER[$pgVar]) && ! isset($_ENV[$pgVar])) {
        $_ENV[$pgVar] = $_SERVER[$pgVar];
    }
}

/*
| Neon (Vercel Postgres) requires the endpoint ID when the client's libpq
| does not support SNI. Pass it via PGOPTIONS, which libpq reads at connect.
| Vercel's pooled hostname appends "-pooler" to the endpoint ID, so strip it.
*/
$pgHost = $_SERVER['PGHOST'] ?? getenv('PGHOST') ?: '';
if ($pgHost === '' && isset($_SERVER['PGHOST_UNPOOLED'])) {
    $pgHost = $_SERVER['PGHOST_UNPOOLED'];
}
if ($pgHost !== '' && str_starts_with($pgHost, 'ep-')) {
    $endpointId = preg_replace('/-pooler$/', '', explode('.', $pgHost)[0]);
    if ($endpointId !== '') {
        putenv('PGOPTIONS=endpoint=' . $endpointId);
    }
}

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && ($error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR))) {
        http_response_code(500);
        header('Content-Type: text/plain');
        echo "FATAL: {$error['message']}\n{$error['file']}:{$error['line']}\n";
    }
});

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    if (! headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain');
    }
    echo 'PROPAGATED: '.get_class($e).': '.$e->getMessage()."\n";
    echo $e->getFile().':'.$e->getLine()."\n";
    echo substr($e->getTraceAsString(), 0, 4000);
}
