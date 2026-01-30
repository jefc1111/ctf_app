<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Team;
use App\Models\User;
use App\Models\Submission;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Qty Teams', Team::count()),
            Stat::make('Qty Participants', User::role('Participant')->count()),
            Stat::make('Qty Submissions', Submission::count())
        ];
    }
}
