<?php
namespace App\Providers\Filament;
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
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Navigation\MenuItem;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Filament\Obras\Pages\Dashboard as AyuntamientosDashboard;

class AyuntamientosPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('ayuntamientos')
            ->path('ayuntamientos')
            ->login()
            ->authGuard('web') // 
            ->authMiddleware([
                Authenticate::class, // 
            ])
            ->topNavigation()
            ->favicon(asset('img/favicon.ico'))
            ->brandLogo(asset('img/logo_diputacionmalaga_horizontal.svg'))
            ->brandLogoHeight('2rem')
            ->maxContentWidth(MaxWidth::Full)
            ->colors([
                'primary' => 'rgb(28, 20, 99)',
            ])
            ->tenant(Team::class,ownershipRelationship: 'members',slugAttribute: 'slug')// 👈 si realmente quieres tenancy
            ->discoverResources(in: app_path('Filament/Ayuntamientos/Resources'), for: 'App\\Filament\\Ayuntamientos\\Resources')
            ->discoverPages(in: app_path('Filament/Ayuntamientos/Pages'), for: 'App\\Filament\\Ayuntamientos\\Pages')
            ->pages([
                AyuntamientosDashboard::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->discoverWidgets(in: app_path('Filament/Ayuntamientos/Widgets'), for: 'App\\Filament\\Ayuntamientos\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->tenantMiddleware([
                CleanTenantUrl::class,
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
            ]);
    }
}