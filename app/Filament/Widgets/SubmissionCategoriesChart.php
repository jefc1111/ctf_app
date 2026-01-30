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
                        '#9B7EDE', // Bright purple
                        '#4E9FE5', // Vivid blue
                        '#5FD4B8', // Bright teal
                        '#F4A261', // Orange
                        '#E76F51', // Coral
                        '#E63946', // Red
                        '#F48FB1', // Pink
                        '#BA68C8', // Orchid
                        '#7E57C2', // Deep purple
                        '#42A5F5', // Sky blue
                        '#26C6DA', // Cyan
                        '#66BB6A', // Green
                        '#9CCC65', // Lime
                        '#FFCA28', // Amber
                        '#FFA726', // Deep orange
                        '#FF7043', // Burnt orange
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
