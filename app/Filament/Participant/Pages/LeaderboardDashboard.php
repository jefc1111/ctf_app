<?php

namespace App\Filament\Participant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Event;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use App\Filament\Widgets\TeamProgressChart;
use App\Filament\Widgets\TeamStandingsTable;

class LeaderboardDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Leaderboard';

    protected ?string $heading = 'Event Leaderboard';

    protected static ?string $slug = 'leaderboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Trophy;

    public ?Event $event = null;
    
    public function mount(): void
    {
        $this->event = auth()->user()->activeEvent();
    }

    // public static function shouldRegisterNavigation(): bool
    // {
    //     $event = auth()->user()->activeEvent();

    //     return !! $event?->isInProgress();
    // }

    protected function getHeaderWidgets(): array
    {
        return [
            TeamProgressChart::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            TeamStandingsTable::class,
        ];
    }
}
