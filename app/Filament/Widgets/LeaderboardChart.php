<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Team;
use App\Enums\SubmissionDecisionStatus;

class LeaderboardChart extends ChartWidget
{
    protected ?string $heading = 'Team scores';

    protected static ?int $sort = 5;

    protected ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $teamScores = Team::with(['submissions.category'])
            ->get()
            ->mapWithKeys(function ($team) {
                $totalPoints = $team->submissions
                    ->where('decision_status', SubmissionDecisionStatus::Approved)
                    ->sum(function ($submission) {
                        return $submission->category->points ?? 0;
                    });
                
                return [ $team->name => $totalPoints ];
            })->sortDesc();

        return [
            'datasets' => [
                [
                    'label' => 'Team Score',
                    'data' => $teamScores->values(),
                    'borderSkipped' => true,
                    'backgroundColor' => [
                        '#dba507', // Gold
                        '#e7b852', // Bronze
                        '#f1cc84', // Silver
                        ...array_map(
                            fn($i) => sprintf(
                                '#%02x%02x%02x', 
                                50 - $i * 5, 
                                50 - $i * 5, 
                                50 - $i * 5
                            ), 
                            range(0, $teamScores->count() - 4)
                        )
                    ],
                ],
            ],
            'labels' => $teamScores->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
