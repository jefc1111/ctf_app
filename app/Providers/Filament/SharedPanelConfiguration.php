<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\Width;
use App\Http\Middleware\Filament\SetUserMenuItems;
use App\Filament\AvatarProviders\JetstreamProfilePhotoAvatarProvider;

class SharedPanelConfiguration
{    public static function applyDefaults(
        Panel $panel, 
        string $panelId, 
        string $panelPath, 
        array $allowedRoles
    ): Panel
    {
        return $panel
            ->default()
            ->id($panelId)
            ->path($panelPath)
            ->login(false)
            ->authGuard('web') // Use Laravel's default web guard
            ->defaultAvatarProvider(JetstreamProfilePhotoAvatarProvider::class)
            ->maxContentWidth(Width::ScreenTwoExtraLarge)
            ->colors([
                'primary' => Color::Amber,
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
                SetUserMenuItems::class
            ])
            ->authMiddleware([
                Authenticate::class,
                'role:'.implode(',', $allowedRoles)
            ]);
            //->strictAuthorization();
            //->unsavedChangesAlerts();
            //->spa();
    }
}
