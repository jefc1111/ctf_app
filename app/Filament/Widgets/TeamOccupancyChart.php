<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Team;

class TeamOccupancyChart extends ChartWidget
{
    protected ?string $heading = 'Team Occupancy';

    protected static ?int $sort = 100;

    protected function getData(): array
    {
        $teamSizes = Team::withCount('members')
            ->get()
            ->groupBy('members_count')
            ->map(fn ($teams) => $teams->count());

        // Fill in missing sizes with 0
        $result = collect(range(1, 4))->mapWithKeys(fn ($size) => [
            $size => $teamSizes->get($size, 0)
        ]);

        return [
            'datasets' => [
                [
                    'label' => 'Qty teams with given occupancy',
                    'data' => $result->values(),
                ],
            ],
            'labels' => $result->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
