<?php

use App\Http\Middleware\AutoLoginForDev;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->priority([
            \App\Http\Middleware\AutoLoginForDev::class, // Harus di atas Authenticate!

        ]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check.role' => CheckRole::class,
            'auto.login.test' => AutoLoginForDev::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
