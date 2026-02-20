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

class ActiveEventPage extends Page
{
    protected string $view = 'filament.participant.pages.active-event';

    protected static ?string $navigationLabel = 'Active Event';

    protected ?string $heading = 'Custom Page Heading';

    protected static ?string $slug = 'active-event';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Flag;

    public ?Event $event = null;
    
    public function mount(): void
    {
        $this->event = auth()->user()->activeEvent();
    }

    // public function getHeading(): ?string
    // {
    //     return $this->event?->name;
    // }

    public function getHeader(): ?View
    {
        return $this->event 
        ? view('components.event.countdown', [
            'event' => $this->event,
            'variant' => 'default',
            'location' => 'participant-page'
        ])
        : view('components.event.no-active-event');
    }
    
    public function eventInfolist(Schema $schema): Schema
    {
        $labelSuffix = $this->event->isInProgress() ? "" : " (TBC)";

        $missingPersonDetailsSection = Section::make('Missing person details'.$labelSuffix);

        $caseDetailsSection = Section::make('Case details'.$labelSuffix);

        if ($this->event->isInProgress()) {
            $missingPersonDetailsSection->inlineLabel()                            
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('age'),
                    TextEntry::make('height'),
                    TextEntry::make('weight')                                  
                ])
                ->headerActions([
                    CreateSubmissionAction::make(),
                ]);

            $caseDetailsSection->collapsed()
                ->schema([
                    TextEntry::make('missing_since')
                        ->inlineLabel()
                        ->since()
                        ->dateTimeTooltip(),
                    TextEntry::make('missing_since_note')
                        ->inlineLabel()
                        ->tooltip("Extra details regarding 'missing since'"),
                    TextEntry::make('missing_from')
                        ->inlineLabel(),
                    TextEntry::make('source_url')
                            ->url(fn (CaseModel $record): ?string => $record->source_url)
                            ->openUrlInNewTab(),
                    TextEntry::make('characteristics'),
                    TextEntry::make('disappearance_details'), 
                    TextEntry::make('notes'),                                
                ]);
        }

        $casesSchema = [
            $missingPersonDetailsSection,
            Section::make('Flag submissions')
                ->inlineLabel()
                ->schema([
                    TextEntry::make('total_submissions')
                        ->default(fn(CaseModel $case) => $case->caseSubmissionDisplayText('total'))                       
                        ->color(fn(CaseModel $case) => $case->caseSubmissionDisplayColor('total'))
                        ->badge(),
                    TextEntry::make('team_submissions')
                        ->default(fn(CaseModel $case) => $case->caseSubmissionDisplayText('team'))                   
                        ->color(fn(CaseModel $case) => $case->caseSubmissionDisplayColor('team'))
                        ->badge(),                                    
                    TextEntry::make('your_submissions')
                        ->default(fn(CaseModel $case) => $case->caseSubmissionDisplayText('user'))
                        ->color(fn(CaseModel $case) => $case->caseSubmissionDisplayColor('user'))
                        ->badge()                                
                ]),
            $caseDetailsSection
        ];

        return $schema
            ->record($this->event)
            ->components([
                Grid::make(4)
                    ->schema([
                        // TextEntry::make('start_time'),
                        // TextEntry::make('end_time'),
                    ]),                
                RepeatableEntry::make('cases')
                    ->label($this->event->isInProgress() ? "Event cases" : "To see case details, please refresh the page when the event has started")
                    ->schema($casesSchema)
                    ->grid(2)
                    ->columnSpan(1),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $event = auth()->user()->activeEvent();

        if ($event?->isInProgress()) {
            return 'in progress';
        }

        if ($event?->isPending()) {
            return 'starting soon';
        }

        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $event = auth()->user()->activeEvent();

        return $event?->isInProgress() 
        ? 'success'
        : 'warning';
    }
}
