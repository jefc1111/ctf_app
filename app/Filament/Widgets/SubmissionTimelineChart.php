<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Submission;

class SubmissionTimelineChart extends ChartWidget
{
    protected ?string $heading = 'Submission Timeline';

    protected function getData(): array
    {
        $submissions = Submission::all();

        // Group submissions by minute and count them
        $timelineData = $submissions->groupBy(function($submission) {
            return $submission->created_at->format('Y-m-d H:i');
        })->map(function($group) {
            return $group->count();
        })->sortKeys(); // Sort by datetime

        return [
            'datasets' => [
                [
                    'label' => 'Submission timeline',
                    'data' => $timelineData->values()->toArray(),
                ],
            ],
            'labels' => $timelineData->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}