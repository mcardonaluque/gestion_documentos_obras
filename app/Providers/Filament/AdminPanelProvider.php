<?php

namespace App\Providers\Filament;

//use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
//use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
//use Althinect\FilamentSpatieRolesPermissions\Resources\UserResource;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Auth\Login as CustomLogin;
use App\Http\Middleware\EnsureSuperAdminForAdminPanel;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Livewire\PersistentDatabaseNotifications;
use App\Filament\Widgets\NotificationsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Support\Enums\Width;
use App\Filament\Pages\Dashboard as AdminDashboard;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Planes-Administración')
            ->collapsedSidebarWidth('18rem')
            ->login(CustomLogin::class)
            ->passwordReset()
            ->databaseNotifications(livewireComponent: PersistentDatabaseNotifications::class)
            ->authGuard('web')
            ->favicon(asset('img/favicon.ico'))
            ->brandLogo(asset('img/logo_diputacionmalaga_horizontal.svg'))
            ->brandLogoHeight('2rem')
            ->maxContentWidth(Width::Full)
            ->plugins([
                FilamentShieldPlugin::make(),

            ])

            ->colors([
                //'primary' => Color::Amber,
                'primary'=>'rgb(28, 20, 99)',
            ])
            ->resources([UserResource::class,
                RoleResource::class,


            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            //->discoverResources(in: app_path('Filament/Obras/Resources'), for: 'App\\Filament\\Obras\\Resources')
            ->discoverPages(in: app_path('Filament/Resources/Pages'), for: 'App\\Filament\\Resources\\Pages')
            //->discoverPages(in: app_path('Filament/Obras/Resources/Pages'), for: 'App\\Filament\\Obras\\Resources\\Pages')
            ->pages([
                AdminDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            ])
           ->authMiddleware([
                Authenticate::class,
             EnsureSuperAdminForAdminPanel::class,
            ])
            ->renderHook('panels::head.end', fn () => view('filament-table-compact-styles'));
           // ->renderHook('panels::head.end', fn () => view('admin-styles'));
    }
}
