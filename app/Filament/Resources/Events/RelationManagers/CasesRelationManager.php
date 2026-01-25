<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Filament\Resources\Events\Resources\Cases\CaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CasesRelationManager extends RelationManager
{
    protected static string $relationship = 'cases';

    protected static ?string $relatedResource = CaseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
