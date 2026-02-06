<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Pages;

use App\Filament\Participant\Resources\TicketPurchases\TicketPurchaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTicketPurchases extends ListRecords
{
    protected static string $resource = TicketPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
