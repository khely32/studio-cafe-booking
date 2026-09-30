<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuth::class,
            'client' => \App\Http\Middleware\ClientAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Registering this callback REPLACES the handler's $request->expectsJson()
        // fallback, so it has to be OR'd back in explicitly. Without it, a 422 from
        // $request->validate() is turned into a 302 redirect for non-api routes.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
