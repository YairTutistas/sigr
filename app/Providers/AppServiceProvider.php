<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

/**
 * Proveedor de servicios principal de la aplicación SIGR.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra servicios de la aplicación en el contenedor.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializa servicios globales de la aplicación.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}

