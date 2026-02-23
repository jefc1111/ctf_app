<?php

namespace App\Filament\Participant\Pages;

use Filament\Pages\Page;
use App\Models\Event;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use App\Models\CaseModel;
use Filament\Schemas\Components\Grid;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use App\Filament\Actions\CreateSubmissionAction;
use Illuminate\Contracts\View\View;
use App\Enums\SubmissionSubset;

class LeaderboardPage extends Page
{
    protected string $view = 'filament.participant.pages.leaderboard';

    protected static ?string $navigationLabel = 'Leaderboard';

    protected ?string $heading = 'Custom Page Heading';

    protected static ?string $slug = 'leaderboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Trophy;

    public ?Event $event = null;
    
    public function mount(): void
    {
        $this->event = auth()->user()->activeEvent();
    }

    public static function shouldRegisterNavigation(): bool
    {
        $event = auth()->user()->activeEvent();

        return !! $event?->isInProgress();
    }

    // public function getHeading(): ?string
    // {
    //     return $this->event?->name;
    // }

    // public function getHeader(): ?View
    // {
    //     return $this->event 
    //     ? view('components.event.countdown', [
    //         'event' => $this->event,
    //         'variant' => 'default',
    //         'location' => 'participant-page'
    //     ])
    //     : view('components.event.no-active-event');
    // }
    
    // public static function getNavigationBadge(): ?string
    // {
    //     $event = auth()->user()->activeEvent();

    //     if ($event?->isInProgress()) {
    //         return 'in progress';
    //     }

    //     if ($event?->isPending()) {
    //         return 'starting soon';
    //     }

    //     return null;
    // }

    // public static function getNavigationBadgeColor(): ?string
    // {
    //     $event = auth()->user()->activeEvent();

    //     return $event?->isInProgress() 
    //     ? 'success'
    //     : 'warning';
    // }

}
