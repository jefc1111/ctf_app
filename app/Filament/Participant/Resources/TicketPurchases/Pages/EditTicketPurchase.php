<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Pages;

use App\Filament\Participant\Resources\TicketPurchases\TicketPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTicketPurchase extends EditRecord
{
    protected static string $resource = TicketPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
