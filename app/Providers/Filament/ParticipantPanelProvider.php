<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use App\Filament\Participant\Pages\EventCasesPage;

class ParticipantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panelId = 'participant';

        $panelPath = 'participant';

        $allowedRoles = [
            'Super Admin',
            'Admin',
            'Event staff',
            'Participant'
        ];
        
        return SharedPanelConfiguration::applyDefaults($panel, $panelId, $panelPath, $allowedRoles)
            ->discoverResources(in: app_path('Filament/Participant/Resources'), for: 'App\Filament\Participant\Resources')
            ->discoverPages(in: app_path('Filament/Participant/Pages'), for: 'App\Filament\Participant\Pages')
            ->discoverWidgets(in: app_path('Filament/Participant/Widgets'), for: 'App\Filament\Participant\Widgets')
            ->widgets([]);
    }
}
