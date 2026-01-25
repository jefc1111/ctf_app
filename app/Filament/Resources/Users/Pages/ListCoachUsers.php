<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\CoachUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoachUsers extends ListRecords
{
    protected static string $resource = CoachUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'Coaches';
    }
}
