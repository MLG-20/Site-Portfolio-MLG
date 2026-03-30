<?php

namespace App\Providers\Filament;

use Filament\Pages\Auth\Login;
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
use Filament\Navigation\NavigationItem;

// --- AJOUTE CETTE LIGNE ---
use Filament\Support\Facades\FilamentIcon;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // --- AJOUTE CE BLOC ---
        // On enregistre les nouvelles icônes pour qu'elles soient disponibles
        FilamentIcon::register([
            'panels::resources.pages.list-records.table.actions.create-action' => 'heroicon-o-plus-circle',
        ]);
        // ----------------------
        
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            
            // On utilise la classe Login qu'on vient d'importer
            ->login(Login::class) 
            ->passwordReset()

            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
            ->navigationItems([
                NavigationItem::make('Statistiques')
                    ->url('https://analytics.google.com/', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-chart-bar')
                    ->group('Rapports')
                    ->sort(3),
            ])
            ->profile();
    }
}