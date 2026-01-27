<?php

namespace App\Filament\Resources\SubmissionCategories\Pages;

use App\Filament\Resources\SubmissionCategories\SubmissionCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubmissionCategories extends ListRecords
{
    protected static string $resource = SubmissionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
