<?php

namespace App\Providers\Filament;

//use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource;
use App\Filament\Obras\Resources\DatosEjecucionObrasResource;
use App\Filament\Obras\Resources\ImportesDeobrasResource;
use App\Filament\Obras\Resources\ImportesPorOrganismoResource;
use App\Filament\Obras\Resources\PlanseguridadysaludResource;
use App\Http\Middleware\CleanTenantUrl;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Str;
use Illuminate\View\Middleware\ShareErrorsFromSession;
//use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Navigation\MenuItem;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;

class ObrasPanelProvider extends PanelProvider
{
    protected function resolveTenant()
    {
        // Logic to resolve the tenant, e.g., fetching from the database or session
        return Auth::user()->currentTeam ?? null; // Example implementation
    }
    protected function getTenantPrefix(): ?string
{    
    $tenant = $this->resolveTenant();
    
    if (!$tenant) {
        return null;
    }
    dd($tenant->name);
    // Usar un slug limpio en lugar del nombre completo
    return Str::slug(trim($tenant->name));
}
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('obras')
            ->path('obras')
            ->brandName('Planes Provinciales')
            ->resources([
                ImportesDeobrasResource::class,
                ImportesPorOrganismoResource::class,
                DatosDeInicioDeObrasResource::class,
                DatosEjecucionObrasResource::class,
               
                //\BezhanSalleh\FilamentShield\Resources\RoleResource::class,

            ])
            ->login()
            ->favicon(asset('img/favicon.ico'))
            ->brandLogo(asset('img/logo_diputacionmalaga_horizontal.svg'))
            ->brandLogoHeight('2rem')
            ->maxContentWidth(MaxWidth::Full)
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                
            ])
            ->colors([
                //'primary' => Color::Amber,
                'primary'=>'rgb(28, 20, 99)',
            ])
            ->tenant(Team::class,ownershipRelationship: 'members',slugAttribute: 'slug')
            //->tenantMiddleware([
            //    \BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant::class,
           // ], isPersistent: true)
            ->discoverResources(in: app_path('Filament/Obras/Resources'), for: 'App\\Filament\\Obras\\Resources')
            ->discoverPages(in: app_path('Filament/Obras/Resources/Pages'), for: 'App\\Filament\\Resources\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Obras/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                CleanTenantUrl::class,
            ])
            
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenantMiddleware([
                CleanTenantUrl::class,
            ])
            ->topNavigation();
    }

}
