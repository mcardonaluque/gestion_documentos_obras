<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use function Illuminate\Log\log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    //protected $policies = [
   //     Role::class => RolePolicy::class,
        //Permission::class => PermissionPolicy::class,
        // Otras políticas...
    //];
   
    public function register(): void
    {
        //
        
    }

    /**
     * Bootstrap any application services.
     */
   
    public function boot(): void
    {
        //
       // Role::on('Obras')->getConnection()->reconnect();
       // Permission::on('Obras')->getConnection()->reconnect();

        // Registrar las políticas para Role y Permission
        //Gate::policy(Role::class, RolePolicy::class);
        //Gate::policy(Permission::class, PermissionPolicy::class);
        FilamentAsset::register([
            Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/app.css'),
        ]);
        Event::listen(\Illuminate\Auth\Events\Attempting::class, function ($event) {
            Log::debug('Intento de login', [
                'email' => $event->credentials['email'],
                'provider' => $event->guard
            ]);
        });
        
        Event::listen(\Illuminate\Auth\Events\Authenticated::class, function ($event) {
            Log::debug('Usuario autenticado', [
                'user' => $event->user->toArray(),
                'guard' => $event->guard
            ]);
        });
        Event::listen(\Illuminate\Auth\Events\Failed::class, function ($event) {
            Log::debug('Autenticación fallida', [
                'email' => $event->credentials['email'],
                'guard' => $event->guard
            ]);
        });
        if (env('APP_DEBUG')) {
            DB::listen(function ($query) {
                Log::debug(
                    'SQL Query',
                    [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]
                );
            });
        }
    }
}