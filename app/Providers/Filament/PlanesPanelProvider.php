<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource;
use App\Filament\Obras\Resources\DatosEjecucionObrasResource;
use App\Filament\Obras\Resources\ImportesDeObrasResource;
use App\Filament\Obras\Resources\ImportesPorOrganismoResource;
use App\Filament\Obras\Resources\PlanseguridadysaludResource;
Use App\Filament\Widgets\UltimasObrasTableWidget;

class PlanesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('planes')
            ->path('planes')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigation(false) // Desactivamos navegación de recursos
            ->navigation(fn (NavigationBuilder $builder) => $this->getTopNavigation($builder))
            ->resources([
                ImportesDeObrasResource::class,
                ImportesPorOrganismoResource::class,
                DatosDeInicioDeObrasResource::class,
                DatosEjecucionObrasResource::class,
               
                //\BezhanSalleh\FilamentShield\Resources\RoleResource::class,

            ])
            ->login()
            ->authGuard('web')
            ->favicon(asset('img/favicon.ico'))
            ->brandLogo(asset('img/logo_diputacionmalaga_horizontal.svg'))
            ->brandLogoHeight('2rem')
            ->maxContentWidth(MaxWidth::Full)
            ->plugins([
               FilamentShieldPlugin::make()
                                                
            ])
            ->discoverResources(in: app_path('Filament/Obras/Resources'), for: 'App\\Filament\\Obras\\Resources')
            ->discoverPages(in: app_path('Filament/Obras/Pages'), for: 'App\\Filament\\Obras\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Obras/Widgets'), for: 'App\\Filament\\Obras\\Widgets')
            ->widgets([
               // Widgets\AccountWidget::class,
               // Widgets\FilamentInfoWidget::class,
               UltimasObrasTableWidget::class,
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
    protected function getTopNavigation(NavigationBuilder $builder): NavigationBuilder
            {
                return $builder->groups([
                NavigationGroup::make('Inicio')
                    ->items([
                        NavigationItem::make('Inicio de Obras')
                            ->icon('heroicon-o-home')
                            ->url('/planes/datos-de-inicio-de-obras'),
                    ]),
                NavigationGroup::make('Proyectos')
                    ->items([
                        NavigationItem::make('Proyectos')
                            ->icon('heroicon-o-rectangle-stack')
                            ->url('/obras/proyectos'),
                        NavigationItem::make('Fases de Proyectos')
                            ->icon('heroicon-o-rectangle-stack')
                            ->url('/obras/fase-de-proyectos'),
                ]),
            
                NavigationGroup::make('Cesión')
                    ->items([
                        NavigationItem::make('Cesión de Obras')
                            ->icon('heroicon-o-rectangle-stack')
                            ->url('/obras/cesion-de-obras'),
                        NavigationItem::make('Entidades de Cesión')
                            ->icon('heroicon-o-rectangle-stack')
                            ->url('/obras/entidadesdecesion'),
                ]),
                NavigationGroup::make('Ejecución')
                ->items([
                    NavigationItem::make('Datos de Ejecución')
                        ->icon('heroicon-o-cog')
                        ->url('/planes/datos-ejecucion-obras'),
                    NavigationItem::make('Certificaciones')
                        ->icon('heroicon-o-cog')
                        ->url('/planes/certificaciones'),
                    NavigationItem::make('Planes de Seguridad y Salud')
                        ->icon('heroicon-o-cog')
                        ->url('/planes/planss'),
            ])
            ]);
         }
    Protected function menuInicio($builder): NavigationGroup
    {
        return $builder->groups([ 
            NavigationGroup::make('Inicio')
            ->items([
                NavigationItem::make('Inicio de Obras')
                    ->icon('heroicon-o-home')
                    ->url('/planes/datos-de-inicio-de-obras'),
            ])
            ]);
    }
    Protected function menuProyectos($builder): NavigationGroup
    {
        return $builder->groups([ 
            NavigationGroup::make('Proyectos')
            ->items([
                NavigationItem::make('Proyectos')
                    ->icon('heroicon-o-rectangle-stack')
                    ->url('/obras/proyectos'),
                NavigationItem::make('Fases de Proyectos')
                    ->icon('heroicon-o-rectangle-stack')
                    ->url('/obras/fase-de-proyectos'),
            ])
        ]);
    }
    protected function menuCesion($builder): NavigationGroup
    {
        return $builder->groups([
            NavigationGroup::make('Cesión')
            ->items([
                NavigationItem::make('Cesión de Obras')
                    ->icon('heroicon-o-rectangle-stack')
                    ->url('/obras/cesion-de-obras'),
                NavigationItem::make('Entidades de Cesión')
                    ->icon('heroicon-o-rectangle-stack')
                    ->url('/obras/entidadesdecesion'),
            ])
        ]);
    }
    Protected function menuEjecucion($builder): NavigationGroup
    {
        return $builder->groups([
            NavigationGroup::make('Ejecución')
            ->items([
                NavigationItem::make('Datos de Ejecución')
                    ->icon('heroicon-o-cog')
                    ->url('/planes/datos-ejecucion-obras'),
                NavigationItem::make('Certificaciones')
                    ->icon('heroicon-o-cog')
                    ->url('/planes/certificaciones'),
                NavigationItem::make('Planes de Seguridad y Salud')
                    ->icon('heroicon-o-cog')
                    ->url('/planes/planss'),
            ])
        ]);
    }
}
