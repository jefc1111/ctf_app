<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\BarChartWidget;
use App\Models\TicketPurchase;

class TicketPurchaseTimelineChart extends BarChartWidget
{
    protected ?string $heading = 'Ticket Purchase Timeline';

    protected static ?int $sort = 20;

    protected function getData(): array
    {
        $ticketPurchases = TicketPurchase::all();

        if (! $ticketPurchases->isEmpty()) {
            // Group submissions by day and count them
            $groupedData = $ticketPurchases->groupBy(function($ticketPurchase) {
                return $ticketPurchase->created_at->format('Y-m-d');
            })->map(function($group) {
                return $group->count();
            });

            // Get the date range
            $startDate = $ticketPurchases->min('created_at')->startOfDay();
            $endDate = $ticketPurchases->max('created_at')->startOfDay();

            // Create array with all dates in range
            $timelineData = collect();
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $dateKey = $currentDate->format('Y-m-d');
                $timelineData[$dateKey] = $groupedData->get($dateKey, 0);
                $currentDate->addDay();
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Qty ticket purchases',
                    'data' => $ticketPurchases->isEmpty() ? [] : $timelineData->values()->toArray(),
                    'backgroundColor' => 'green',
                    'borderColor' => 'green',
                    'barThickness' => 2
                ],
            ],
            'labels' => $ticketPurchases->isEmpty() ? [] : $timelineData->keys()->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}