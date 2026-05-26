<?php
namespace App\Providers\Filament;

use App\Filament\Auth\Login as CustomLogin;
use App\Filament\Livewire\PersistentDatabaseNotifications;
use App\Filament\Widgets\NotificationsWidget;
use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use App\Filament\Ayuntamientos\Resources\Expedientes\ExpedienteResource;
use App\Filament\Obras\Resources\ImportesDeObras\ImportesDeObrasResource;
use App\Filament\Obras\Pages\Dashboard as AyuntamientosDashboard;
use App\Http\Middleware\CleanTenantUrl;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AyuntamientosPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('ayuntamientos')
            ->path('ayuntamientos')
            ->login(CustomLogin::class)
            ->passwordReset()
            ->authGuard('web') //
            ->authMiddleware([
                Authenticate::class, //
            ])
            ->resources([
                ImportesDeObrasResource::class,
                DocumentoexpedienteResource::class,
                ExpedienteResource::class,
            ])
            ->topNavigation()
            ->databaseNotifications(livewireComponent: PersistentDatabaseNotifications::class)
            ->favicon(asset('img/favicon.ico'))
            ->brandLogo(asset('img/logo_diputacionmalaga_horizontal.svg'))
            ->brandLogoHeight('2rem')
            ->maxContentWidth(Width::Full)
            ->colors([
                'primary' => 'rgb(28, 20, 99)',
            ])
            ->tenant(Team::class, ownershipRelationship: 'members', slugAttribute: 'slug')
            ->discoverResources(in: app_path('Filament/Ayuntamientos/Resources'), for: 'App\\Filament\\Ayuntamientos\\Resources')
            ->discoverPages(in: app_path('Filament/Ayuntamientos/Pages'), for: 'App\\Filament\\Ayuntamientos\\Pages')
            ->pages([
                AyuntamientosDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Ayuntamientos/Widgets'), for: 'App\\Filament\\Ayuntamientos\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
                NotificationsWidget::class,
                \App\Filament\Widgets\ExpedientesTable::class,
                \App\Filament\Widgets\DocumentosTable::class,

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
