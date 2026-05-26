<?php

namespace App\Providers\Filament;

//use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Obras\Resources\DatosDeInicioDeObras\DatosDeInicioDeObrasResource;
use App\Filament\Obras\Resources\DatosEjecucionObras\DatosEjecucionObrasResource;
use App\Filament\Obras\Resources\Expedientes\ExpedienteResource;
use App\Filament\Obras\Resources\ImportesDeObras\ImportesDeObrasResource;
use App\Filament\Obras\Resources\ImportesPorOrganismos\ImportesPorOrganismoResource;
use App\Http\Middleware\CleanTenantUrl;
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
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;
use Filament\Enums\UserMenuPosition;
use App\Filament\Livewire\PersistentDatabaseNotifications;
use App\Filament\Auth\Login as CustomLogin;
use App\Filament\Widgets\UltimasObrasTableWidget;
use App\Filament\Widgets\NotificationsWidget;
use App\Filament\Obras\Pages\Dashboard as ObrasDashboard;
use App\Filament\Obras\Resources\Documentoexpedientes\DocumentoexpedienteResource;



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
    //dd($tenant->name);
    // Usar un slug limpio en lugar del nombre completo
    return Str::slug(trim($tenant->name));
}
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('obras')
            ->path('obras')
            ->authGuard('web')
            ->brandName('Planes Provinciales')
            ->navigation(false)
            ->userMenu(position: UserMenuPosition::Topbar)
            ->databaseNotifications(livewireComponent: PersistentDatabaseNotifications::class)
            ->databaseNotificationsPolling('30s')
            ->resources([
                ImportesDeObrasResource::class,
                ImportesPorOrganismoResource::class,
                DatosDeInicioDeObrasResource::class,
                DatosEjecucionObrasResource::class,
                DocumentoexpedienteResource::class,
                ExpedienteResource::class,

                //\BezhanSalleh\FilamentShield\Resources\RoleResource::class,

            ])
            ->login(CustomLogin::class)
            ->passwordReset()
            ->favicon(asset('img/favicon.ico'))
            ->brandLogo(asset('img/logo_diputacionmalaga_horizontal.svg'))
            ->brandLogoHeight('2rem')
            ->maxContentWidth(Width::Full)
            ->plugins([
                FilamentShieldPlugin::make()

            ])

            ->colors([
                //'primary' => Color::Amber,
                'primary' => Color::rgb('rgb(28, 20, 99)'),
            ])

           // ->tenant(Team::class,ownershipRelationship: 'members',slugAttribute: 'slug')
            //->tenantMiddleware([
            //    \BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant::class,
           // ], isPersistent: true)
            ->discoverResources(in: app_path('Filament/Obras/Resources'), for: 'App\\Filament\\Obras\\Resources')
            ->discoverPages(in: app_path('Filament/Obras/Resources/Pages'), for: 'App\\Filament\\Resources\\Pages')
            ->pages([
                ObrasDashboard::class,
            ])
           //->viteTheme('resources/css/custom.css')
           //->theme('resources/css/filament/custom.css')
            ->discoverWidgets(in: app_path('Filament/Obras/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
              //  AccountWidget::class,
              //  FilamentInfoWidget::class,

                UltimasObrasTableWidget::class,
                NotificationsWidget::class,

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
           // ->tenantMiddleware([
           //     CleanTenantUrl::class,
           // ])
            //->sidebarCollapsibleOnDesktop();;
            ->globalSearch(false)
            ->renderHook('panels::head.end', fn () => view('obras-styles'))
            ->renderHook('panels::head.end', fn () => view('filament-table-compact-styles'))
            ->topNavigation();



    }
/*public function boot(): void
{
    FilamentView::registerRenderHook(
        PanelsRenderHook::TOPBAR_AFTER, fn () => view('obras-styles')
    );
}*/

}
