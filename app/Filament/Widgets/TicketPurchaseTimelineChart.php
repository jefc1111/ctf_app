<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TicketPurchase;

class TicketPurchaseTimelineChart extends ChartWidget
{
    protected ?string $heading = 'Ticket Purchase Timeline';

    protected static ?int $sort = 20;

    protected function getData(): array
    {
        $ticketPurchases = TicketPurchase::all();

        // Group submissions by minute and count them
        $timelineData = $ticketPurchases->groupBy(function($ticketPurchase) {
            return $ticketPurchase->created_at->format('Y-m-d');
        })->map(function($group) {
            return $group->count();
        })->sortKeys(); // Sort by datetime

        return [
            'datasets' => [
                [
                    'label' => 'Ticket Purchase timeline',
                    'data' => $timelineData->values()->toArray(),
                    'backgroundColor' => 'green',
                    'borderColor' => 'green'

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