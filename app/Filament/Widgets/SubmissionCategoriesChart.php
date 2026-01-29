<?php

namespace App\Filament\Widgets;

use App\Models\SubmissionCategory;
use Filament\Widgets\ChartWidget;

class SubmissionCategoriesChart extends ChartWidget
{
    protected ?string $heading = 'Submission Category Share';

    protected string $color = 'info';

    protected function getData(): array
    {
        $categories = SubmissionCategory::withCount('submissions')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Qty submissions',
                    'data' => $categories->map(fn($c) => $c->submissions_count),
                    'backgroundColor' => [
                        '#8B7BA8', // Muted purple
                        '#6B9AC4', // Dusty blue
                        '#88B3A6', // Sage green
                        '#C4A77D', // Warm tan
                        '#D4918B', // Dusty rose
                        '#9B8B7E', // Warm gray
                        '#7BA8A3', // Teal
                        '#B89B84', // Sandy beige
                        '#A08DB5', // Soft lavender
                        '#7D9BB3', // Steel blue
                        '#96B88F', // Muted olive
                        '#C9A88A', // Caramel
                        '#B88E9D', // Mauve
                        '#8FA39A', // Seafoam
                        '#A89C8B', // Taupe
                        '#87A3B8', // Powder blue
                    ],
                ],
            ],
            'labels' => $categories->map(fn($c) => $c->name)
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
