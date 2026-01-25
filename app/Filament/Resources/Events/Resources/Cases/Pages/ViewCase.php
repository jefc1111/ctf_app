<?php

namespace App\Filament\Resources\Events\Resources\Cases\Pages;

use App\Filament\Resources\Events\Resources\Cases\CaseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCase extends ViewRecord
{
    protected static string $resource = CaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
