<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;

class AdminPanelProvider extends PanelProvider
{   
    public function panel(Panel $panel): Panel
    {
        $panelId = 'admin';

        $panelPath = 'admin';

        // Just because a role has access to this panel doesn't mean users of all these roles
        // will be able to do all things (e.g., Event Staff perhaps can't delete Submission Categories, etc etc...) 
        $allowedRoles = [
            'Super Admin',
            'Admin',
            'Event staff'
        ];
        
        return SharedPanelConfiguration::applyDefaults($panel, $panelId, $panelPath, $allowedRoles)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('CTF')
                    ->collapsible(false),
                NavigationGroup::make()
                    ->label('Admin')
                    ->collapsible(false)            
            ]);
    }
}
