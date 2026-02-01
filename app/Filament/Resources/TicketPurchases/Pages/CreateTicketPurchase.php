<?php

namespace App\Filament\Resources\TicketPurchases\Pages;

use App\Filament\Resources\TicketPurchases\TicketPurchaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketPurchase extends CreateRecord
{
    protected static string $resource = TicketPurchaseResource::class;
}
