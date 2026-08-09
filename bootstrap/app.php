<?php

use App\Http\Middleware\CambiarConexionBaseDatos;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiAuthenticated;
use App\Http\Middleware\EsAdministrador;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // alias api.auth to the ApiAuthenticated middleware
        $middleware->alias([
            'api.auth' => ApiAuthenticated::class,
            'es.admin' => EsAdministrador::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();