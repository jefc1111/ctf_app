<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Operation;
use Filament\Forms\Components\Select;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('captain_id')
                    ->label('Captain')
                    ->searchable()
                    ->relationship(name: 'captain', titleAttribute: 'name')
                    ->required(),
                Select::make('coach_id')
                    ->label('Coach')
                    ->searchable()
                    ->relationship(name: 'coach', titleAttribute: 'name')
            ]);
    }
}
