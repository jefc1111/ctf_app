<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Pages;

use App\Filament\Participant\Resources\TicketPurchases\TicketPurchaseResource;
use App\Filament\Actions\ClaimTicketAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListTicketPurchases extends ListRecords
{
    protected static string $resource = TicketPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ClaimTicketAction::make(),
        ];
    }

    // This is needed so that actions can do `$this->getLivewire()->dispatch('refreshTable');`
    // Without it the table does not update
    #[On('refreshTable')]
    public function refreshTable(): void
    {
    }
}
