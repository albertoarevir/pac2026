<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
// Importación de Modelos
use App\Models\Pac;
use App\Models\Modalidad;
// Importación de Observers
use App\Observers\PacObserver;
use App\Observers\ModalidadObserver;
Use App\Observers\OrdenObserver;
use App\Models\Orden;

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
        // 1. Forzar el uso de estilos de Bootstrap 5 en la paginación
        Paginator::useBootstrapFive();

        // 2. Vincular el Observer para la tabla PAC
        Pac::observe(PacObserver::class);

        // 3. Vincular el Observer para la tabla MODALIDADS
        Modalidad::observe(ModalidadObserver::class);
    }
}