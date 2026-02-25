<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Submission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\SubmissionDecisionStatus;

class TeamProgressChart extends ChartWidget
{
    // protected static ?string $heading = 'Team Progress';
    // protected static ?string $pollingInterval = '30s';
    
    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '480px'; 

    protected function getData(): array
    {
        $event = Auth::user()->activeEvent();
        
        if (!$event) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Get all teams with their approved submissions
        $teams = $event->teams()->with(['submissions' => function ($query) {
            $query->where('decision_status', SubmissionDecisionStatus::Approved)
                  ->with('category');
        }])->get();

        $now = now();

        // Generate time labels (every 5 minutes between start and now/end)
        $endTime = $event->end_time < $now ? $event->end_time : $now;
        $interval = 1; // minutes
        $labels = [];
        $current = $event->start_time->copy();
        
        while ($current <= $endTime) {
            $labels[] = $current->format('H:i');
            $current->addMinutes($interval);
        }

        $datasets = [];
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
        
        foreach ($teams as $index => $team) {
            $pointsOverTime = [];
            $runningTotal = 0;
            
            // Initialize cumulative points at start time
            $pointsOverTime[] = 0;
            
            $currentTime = $event->start_time->copy()->addMinutes($interval);
            
            while ($currentTime <= $endTime) {
                // Sum points for submissions up to this time
                $submissionsUpToNow = $team->submissions->filter(function ($submission) use ($currentTime) {
                    return $submission->created_at <= $currentTime;
                });
                
                $runningTotal = $submissionsUpToNow->sum(function ($submission) {
                    return $submission->category->points ?? 0;
                });
                
                $pointsOverTime[] = $runningTotal;
                $currentTime->addMinutes($interval);
            }

            $datasets[] = [
                'label' => $team->name,
                'data' => $pointsOverTime,
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => 'transparent',
                'tension' => 0.1,
                'fill' => false,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Total Points',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Time (HH:MM)',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}