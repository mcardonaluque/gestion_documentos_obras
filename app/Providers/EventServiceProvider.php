<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\SystemEventOccurred;
use App\Listeners\HandleSystemEventNotifications;

/**
 * Proveedor de eventos de dominio.
 *
 * Declara el mapeo entre eventos emitidos por la aplicación y sus listeners,
 * permitiendo desacoplar la lógica de negocio de la auditoría y la notificación.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Eventos registrados y listeners asociados.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
       SystemEventOccurred::class => [
        HandleSystemEventNotifications::class,
        ],
    ];

    public function register(): void
    {
        //

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
