<?php

namespace App\Filament\Widgets;

use App\Models\Team;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\SubmissionDecisionStatus;

class TeamStandingsTable extends BaseWidget
{
    protected static ?string $heading = 'Team Standings';
    protected static ?int $sort = 2; // Show below chart

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $event = Auth::user()->activeEvent();

        if (!$event) {
            return $table->query(Team::query()); // Empty table
        }

        return $table
            ->query(
                Team::query()
                    ->where('event_id', $event->id)
                    ->withCount([
                        'submissions as solves_count' => function ($query) {
                            $query->where('decision_status', SubmissionDecisionStatus::Approved);
                        }
                    ])
                    ->withSum([
                        'submissions as total_points' => function ($query) {
                            $query->where('decision_status', SubmissionDecisionStatus::Approved)
                                ->join('submission_categories', 'submissions.submission_category_id', '=', 'submission_categories.id');
                        }
                    ], 'submission_categories.points')
            )
            ->defaultSort('total_points', 'desc')
            ->columns([
                // Tables\Columns\TextColumn::make('position')
                //     ->label('#')
                //     ->state(function ($record, $livewire) {
                //         $teams = $livewire->getTableQuery()->get();
                //         $position = $teams->search(function ($team) use ($record) {
                //             return $team->id === $record->id;
                //         });

                //         return $position !== false ? $position + 1 : '-';
                //     }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Team')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_points')
                    ->label('Total Points')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('solves_count')
                    ->label('Qty Approved Submissions')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                // We'll add filters later as requested
            ])
            ->actions([
                // We'll add actions later as requested
            ])
            ->poll('30s');
    }

    protected function getTableQuery(): ?Builder
    {
        $event = Auth::user()->activeEvent();

        if (!$event) {
            return null;
        }

        return Team::query()
            ->where('event_id', $event->id)
            ->withCount([
                'submissions as solves_count' => function ($query) {
                    $query->where('decision_status', SubmissionDecisionStatus::Approved);
                }
            ])
            ->withSum([
                'submissions as total_points' => function ($query) {
                    $query->where('decision_status', SubmissionDecisionStatus::Approved)
                        ->join('submission_categories', 'submissions.submission_category_id', '=', 'submission_categories.id');
                }
            ], 'submission_categories.points')
            ->orderByDesc('total_points');
    }
}