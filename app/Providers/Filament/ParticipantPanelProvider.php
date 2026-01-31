<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\Width;
use Filament\Actions\Action;

class ParticipantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $id = 'participant';

        $path = 'participant';
        
        $allowedRoles = [
            'Super Admin',
            'Admin',
            'Event staff',
            'Participant'            
        ];

        return $panel
            ->id($id)
            ->path($path)
            ->login(false)
            ->authGuard('web') // Use Laravel's default web guard
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Participant/Resources'), for: 'App\Filament\Participant\Resources')
            ->discoverPages(in: app_path('Filament/Participant/Pages'), for: 'App\Filament\Participant\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Participant/Widgets'), for: 'App\Filament\Participant\Widgets')
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
                'role:'.implode(',', $allowedRoles)
            ])
            ->userMenuItems([
                'profile' => fn (Action $action) => $action->url('/user/profile')
                // Logout is handled by Filament automatically
            ])
            ->maxContentWidth(Width::ScreenTwoExtraLarge);
    }
}
