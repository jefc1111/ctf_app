<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Pages;

use App\Filament\Participant\Resources\TicketPurchases\TicketPurchaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketPurchase extends CreateRecord
{
    protected static string $resource = TicketPurchaseResource::class;
}
