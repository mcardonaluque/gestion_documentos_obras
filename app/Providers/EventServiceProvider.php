<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\SystemEventOccurred;
use App\Listeners\HandleSystemEventNotifications;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
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
