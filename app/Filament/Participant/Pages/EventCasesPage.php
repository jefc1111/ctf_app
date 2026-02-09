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

class EventCasesPage extends Page
{
    protected string $view = 'filament.participant.pages.event-cases';

    protected static ?string $navigationLabel = 'Live Event View';

    protected ?string $heading = 'Custom Page Heading';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Flag;

    public function mount(): void
    {
        $this->event = auth()->user()->activeEvent();
    }

    public function getHeading(): ?string
    {
        return $this->event?->name;
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
                            ->url(fn (CaseModel $record): string => $record->source_url)
                            ->openUrlInNewTab(),
                    TextEntry::make('characteristics'),
                    TextEntry::make('disappearance_details'), 
                    TextEntry::make('notes'),                                
                ]);
        }

        $casesSchema = [
            $missingPersonDetailsSection,
            Section::make('Flag submissions (NOTE: placeholder stats only')
                ->inlineLabel()
                ->schema([
                    TextEntry::make('total_submissions')
                        ->default(fn() => rand(10, 50).' ('.(rand(80, 500)*10).' points)') // @TODO make this real
                        ->badge(),
                    TextEntry::make('team_submissions')
                        ->default(fn() => rand(5, 10).' ('.(rand(15, 50)*10).' points)') // @TODO make this real
                        ->badge(),                                    
                    TextEntry::make('your_submissions')
                        ->default(fn() => rand(1, 5).' ('.(rand(0, 15)*10).' points)') // @TODO make this real
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

        return $event && $event->isInProgress() ? 'in progress' : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
