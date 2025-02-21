<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;

use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
    }
}
