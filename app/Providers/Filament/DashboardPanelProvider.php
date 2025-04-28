<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentColor::register([
            'indigo' => Color::Indigo,
        ]);

        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->databaseNotifications()
            ->userMenuItems([
                // Subproceso
                UserMenuItem::make()
                    ->label(function () {
                        return auth()->user()?->ownerOfSubProcess()?->title;
                    })
                    ->icon('heroicon-o-puzzle-piece') // ícono para subproceso
                    ->url(null)
                    ->sort(2)
                    ->hidden(fn () => ! auth()->user()?->ownerOfSubProcess() ||
                        auth()->user()?->hasRole('super_admin')
                    ),
                // Proceso
                UserMenuItem::make()
                    ->label(function () {
                        $subProcess = auth()->user()?->ownerOfSubProcess();

                        return $subProcess?->process?->title;
                    })
                    ->icon('heroicon-o-rectangle-group') // ícono para proceso
                    ->url(null)
                    ->sort(1)
                    ->hidden(fn () => ! auth()->user()?->ownerOfSubProcess() ||
                        auth()->user()?->hasRole('super_admin')
                    ),
            ]);
    }
}
