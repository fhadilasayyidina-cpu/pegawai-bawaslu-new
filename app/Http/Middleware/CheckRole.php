<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

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
        if (! Auth::user()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah role user cocok dengan role yang dibutuhkan
        if (Auth::user()->role->value !== $role) {
            // Redirect ke dashboard yang sesuai dengan role user
            $dashboard = match (Auth::user()->role->value) {
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
