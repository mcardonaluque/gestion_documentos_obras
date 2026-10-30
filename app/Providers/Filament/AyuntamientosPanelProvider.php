<?php
namespace App\Providers\Filament;

use App\Filament\Auth\Login as CustomLogin;
use App\Filament\Livewire\PersistentDatabaseNotifications;
use App\Filament\Widgets\NotificationsWidget;
use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use App\Filament\Ayuntamientos\Resources\Expedientes\ExpedienteResource;
use App\Filament\Obras\Pages\Dashboard as AyuntamientosDashboard;
use App\Http\Middleware\CleanTenantUrl;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Configura el panel de ayuntamientos de Filament (id `ayuntamientos`,
 * ruta /ayuntamientos), orientado a los usuarios municipales.
 *
 * Es multi-tenant por `Team` (ayuntamiento): registra los recursos de
 * expedientes y documentos del espacio Ayuntamientos, una navegación
 * superior por fases documentales y el middleware `CleanTenantUrl` para
 * sanear las URLs de tenant. Se registra en bootstrap\providers.php.
 */
class AyuntamientosPanelProvider extends PanelProvider
{
    /**
     * Define identidad, tenancy, recursos, navegación y middleware del panel.
     */
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
                DocumentoexpedienteResource::class,
                ExpedienteResource::class,
            ])
            ->navigation(false)
            ->navigation(fn (NavigationBuilder $builder) => $this->getTopNavigation($builder))
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

    /**
     * Construye la navegación superior del panel, agrupada en
     * Expedientes y Documentación (enlaces filtrados por fase documental).
     */
    protected function getTopNavigation(NavigationBuilder $builder): NavigationBuilder
    {
        return $builder->groups([
            NavigationGroup::make('Expedientes')
                ->items([
                    NavigationItem::make('Expedientes')
                        ->icon('heroicon-o-folder-open')
                        ->url(ExpedienteResource::getUrl()),
                ]),
            NavigationGroup::make('Documentación')
                ->items([
                    NavigationItem::make('Documentos del expediente')
                        ->icon('heroicon-o-document-text')
                        ->url(DocumentoexpedienteResource::getUrl('index')),
                    NavigationItem::make('Proyecto')
                        ->icon('heroicon-o-document')
                        ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'proyecto'])),
                    NavigationItem::make('Aprobación')
                        ->icon('heroicon-o-document-check')
                        ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'aprobacion'])),
                    NavigationItem::make('Cesión')
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'cesion'])),
                    NavigationItem::make('Contratación')
                        ->icon('heroicon-o-document-text')
                        ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'contratacion'])),
                    NavigationItem::make('Ejecución')
                        ->icon('heroicon-o-document-chart-bar')
                        ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'ejecucion'])),
                    NavigationItem::make('Justificación')
                        ->icon('heroicon-o-document-plus')
                        ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'justificacion'])),
                ]),
        ]);
    }
}
