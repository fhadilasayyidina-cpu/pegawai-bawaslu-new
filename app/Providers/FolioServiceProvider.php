<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;

class FolioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Folio::path(resource_path('views/pages'))->middleware([
            // Semua halaman di folder 'admin' wajib login & role admin
            '/admin/*' => [
                'auth',
                'verified',
                'role:admin',
            ],
            
            // Halaman lainnya cukup auth saja
            '*' => [
                
            ],
        ]);
    }
}
