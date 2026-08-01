<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AutoLoginForDev
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Hanya aktif di lingkungan lokal untuk keamanan
        if (app()->environment('local') || app()->environment('development')) {
            $adminUser = User::where('role', 'admin')->first();

            Log::debug("AutoLoginForDev Middleware called");
            if (!Auth::check()) {
                Auth::login($adminUser);
            }
        }

        return $next($request);
    }
}
