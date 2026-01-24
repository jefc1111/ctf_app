<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Team;
use App\Models\User;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Qty Teams', Team::count()),
            Stat::make('Qty Contestants', User::role('Participant')->count())
        ];
    }
}
