<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global HTTP middleware
        $middleware->trustProxies(at: '*');

        // Web middleware group — append custom middleware
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Middleware aliases are declared per module from Wave 1 (M03, M25).
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
