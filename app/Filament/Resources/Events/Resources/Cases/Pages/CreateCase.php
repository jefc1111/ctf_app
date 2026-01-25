<?php

namespace App\Filament\Resources\Events\Resources\Cases\Pages;

use App\Filament\Resources\Events\Resources\Cases\CaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCase extends CreateRecord
{
    protected static string $resource = CaseResource::class;
}
