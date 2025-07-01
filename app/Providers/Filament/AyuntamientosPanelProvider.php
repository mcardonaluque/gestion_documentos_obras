<?php

namespace App\Providers\Filament;

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
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AyuntamientosPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('ayuntamientos')
            ->path('ayuntamientos')
            ->login()
            ->colors([
               //'primary' => Color::Amber,
                'primary'=>'rgb(28, 20, 99)',
            ])
            ->tenant(Team::class)
            ->discoverResources(in: app_path('Filament/Ayuntamientos/Resources'), for: 'App\\Filament\\Ayuntamientos\\Resources')
            ->discoverPages(in: app_path('Filament/Ayuntamientos/Pages'), for: 'App\\Filament\\Ayuntamientos\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                
            ])
            ->discoverWidgets(in: app_path('Filament/Ayuntamientos/Widgets'), for: 'App\\Filament\\Ayuntamientos\\Widgets')
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
            ]);
    }
}
