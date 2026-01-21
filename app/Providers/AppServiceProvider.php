<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

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
        Vite::prefetch(concurrency: 3);

        \App\Models\ActivoFijo::observe(\App\Observers\ActivoFijoObserver::class);
        \App\Models\ActivoFijo::observe(\App\Observers\AuditoriaObserver::class);
        \App\Models\Mantenimiento::observe(\App\Observers\MantenimientoObserver::class);
        \App\Models\BajaActivo::observe(\App\Observers\BajaObserver::class);
        \App\Models\Movimiento::observe(\App\Observers\MovimientoObserver::class);
    }
}
