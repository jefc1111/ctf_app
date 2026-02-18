<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\View;

FilamentView::registerRenderHook(
    PanelsRenderHook::GLOBAL_SEARCH_BEFORE, // or TOPBAR_START, TOPBAR_END, etc.
    fn () => auth()->user()?->activeEvent()
    ? auth()->user()->activeEvent()->name.View::make('components.event.countdown', [
        'event' => auth()->user()->activeEvent(),
        'variant' => 'compact',
        'id' => 'header-countdown',
        'location' => 'header'
    ])
    : null
);

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind custom login response
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
