<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Charts\LivewirePieChart;
use App\Livewire\Charts\LivewireColumnChart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            $url = request()->root();
            config(['app.url' => $url]);
            \Illuminate\Support\Facades\URL::forceRootUrl($url);

            // Fix for Livewire in subdirectory
            $path = request()->getBasePath();
            if ($path) {
                config(['livewire.asset_url' => $url]);
                \Livewire\Livewire::setUpdateRoute(function ($handle) use ($path) {
                    return \Illuminate\Support\Facades\Route::post($path.'/livewire/update', $handle);
                });
            }
        }

        Livewire::component('livewire-pie-chart', LivewirePieChart::class);
        Livewire::component('livewire-column-chart', LivewireColumnChart::class);
    }
}
