<?php

namespace App\Providers\Filament;

//use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
//use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
//use Althinect\FilamentSpatieRolesPermissions\Resources\UserResource;
use App\Filament\Resources\UserResource;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
//use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Navigation\MenuItem;
use Filament\Support\Enums\MaxWidth;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Planes-Administración')
            ->login()
            ->authGuard('web') 
            ->favicon(asset('img/favicon.ico'))
            ->brandLogo(asset('img/logo_diputacionmalaga_horizontal.svg'))
            ->brandLogoHeight('2rem')
            ->maxContentWidth(MaxWidth::Full)
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                
            ])
            ->colors([
                //'primary' => Color::Amber,
                'primary'=>'rgb(28, 20, 99)',
            ])
            ->resources([UserResource::class,
                \App\Filament\Obras\Resources\DatosDeInicioDeObrasResource::class,
                \App\Filament\Obras\Resources\ImportesDeobrasResource::class, 
                \App\Filament\Obras\Resources\ImportesPorOrganismoResource::class,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverResources(in: app_path('Filament/Obras/Resources'), for: 'App\\Filament\\Obras\\Resources')
            ->discoverPages(in: app_path('Filament/Resources/Pages'), for: 'App\\Filament\\Resources\\Pages')
            ->discoverPages(in: app_path('Filament/Obras/Resources/Pages'), for: 'App\\Filament\\Obras\\Resources\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
            ])
           ->authMiddleware([
                Authenticate::class,
            ])
            ->topNavigation();
    }
}
