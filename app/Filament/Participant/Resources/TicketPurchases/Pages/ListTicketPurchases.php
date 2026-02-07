<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Pages;

use App\Filament\Participant\Resources\TicketPurchases\TicketPurchaseResource;
use App\Filament\Actions\ClaimTicketAction;
use Filament\Resources\Pages\ListRecords;

class ListTicketPurchases extends ListRecords
{
    protected static string $resource = TicketPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ClaimTicketAction::make(),
        ];
    }
}
