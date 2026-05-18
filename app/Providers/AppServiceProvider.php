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
        Livewire::component('livewire-pie-chart', LivewirePieChart::class);
        Livewire::component('livewire-column-chart', LivewireColumnChart::class);
    }
}
