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

putenv('APP_NAME=56\'30 Studio Cafe');
putenv('APP_ENV=production');
putenv('APP_DEBUG=true');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('CACHE_DRIVER=array');
putenv('CACHE_STORE=array');
putenv('LOG_CHANNEL=stderr');
putenv('SESSION_DRIVER=cookie');
putenv('VIEW_COMPILED_PATH=/tmp');

require __DIR__ . '/../public/index.php';
