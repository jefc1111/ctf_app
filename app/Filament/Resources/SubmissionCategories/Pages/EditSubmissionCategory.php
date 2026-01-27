<?php

namespace App\Filament\Resources\SubmissionCategories\Pages;

use App\Filament\Resources\SubmissionCategories\SubmissionCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSubmissionCategory extends EditRecord
{
    protected static string $resource = SubmissionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
