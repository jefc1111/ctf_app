<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

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
                    ->relationship(
                        name: 'captain', 
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role('Participant')
                    )
                    ->required(),
                Select::make('coach_id')
                    ->label('Coach')
                    ->searchable()
                    ->relationship(
                        name: 'coach', 
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role(['Coach', 'Senior Coach'])
                    ),
                TextInput::make('join_code')
                    ->disabled(),
                Select::make('members')
                    ->multiple()
                    ->relationship(
                        name: 'members', 
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role('Participant')
                    )
                    ->searchable(['name', 'email'])
                    ->searchPrompt('Search participants by their name or email address')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} (email: {$record->email})")
                    ->maxItems(4),

            ]);
    }
}
