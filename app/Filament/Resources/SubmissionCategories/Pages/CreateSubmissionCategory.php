<?php

namespace App\Filament\Resources\SubmissionCategories\Pages;

use App\Filament\Resources\SubmissionCategories\SubmissionCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubmissionCategory extends CreateRecord
{
    protected static string $resource = SubmissionCategoryResource::class;
}
