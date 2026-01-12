<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika user belum login
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah role user cocok dengan role yang dibutuhkan
        if (auth()->user()->role->value !== $role) {
            // Redirect ke dashboard yang sesuai dengan role user
            $dashboard = match (auth()->user()->role->value) {
                'admin' => '/admin/dashboard',
                'operator' => '/operator/dashboard',
                'pegawai' => '/pegawai/dashboard',
                default => '/dashboard',
            };

            return redirect($dashboard);
        }

        return $next($request);
    }
}
