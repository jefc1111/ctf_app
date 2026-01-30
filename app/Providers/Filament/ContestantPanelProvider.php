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

class ContestantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $id = 'contestant';

        $path = 'contestant';

        // Just because a role has access to this panel doesn't mean users of all these roles
        // will be able to do all things (e.g., Event Staff perhaps can't delete Submission Categories, etc etc...) 
        $allowedRoles = [
            'Super Admin',
            'Admin',
            'Event staff',
            'Contestant'            
        ];

        return $panel
            ->id($id)
            ->path($path)
            ->login(false)
            ->authGuard('web') // Use Laravel's default web guard
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Contestant/Resources'), for: 'App\Filament\Contestant\Resources')
            ->discoverPages(in: app_path('Filament/Contestant/Pages'), for: 'App\Filament\Contestant\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Contestant/Widgets'), for: 'App\Filament\Contestant\Widgets')
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
