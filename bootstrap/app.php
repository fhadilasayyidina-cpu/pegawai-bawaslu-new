<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();

            if (!$user) return '/login';

            // Gunakan match untuk pengecekan role
            return match ($user->role) {
                \App\Enums\Role::ADMIN    => '/admin/dashboard',
                \App\Enums\Role::OPERATOR => '/operator/dashboard',
                \App\Enums\Role::PEGAWAI  => '/pegawai/dashboard',

                // Jika role tidak dikenal, logout dan buang ke login
                default => (function () {
                    auth()->logout();
                    request()->session()->invalidate();
                    request()->session()->regenerateToken();
                    return '/login';
                })(),
            };
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
