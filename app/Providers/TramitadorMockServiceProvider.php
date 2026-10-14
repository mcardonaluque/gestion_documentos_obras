<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Tramitador\Mock\TramitadorMock;
use Illuminate\Support\ServiceProvider;

/**
 * Activa el mock del tramitador cuando TRAMITADOR_MOCK=true.
 */
class TramitadorMockServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('tramitador.mock.enabled') === true) {
            TramitadorMock::register();
        }
    }
}
