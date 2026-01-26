<?php

namespace App\Filament\Resources\Users\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Operation;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CoachUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                Select::make('mentor_id')
                    ->label('Mentor')
                    ->searchable()
                    ->relationship(
                        name: 'mentor', 
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role(['Coach', 'Senior Coach'])
                    ),
                TextInput::make('password')
                    ->required()
                    ->hiddenOn(Operation::Edit),
                Select::make('roles')
                    ->multiple(false)
                    ->relationship(titleAttribute: 'name')
                    ->required()
                    ->preload()
                    ->hiddenOn(Operation::Edit),
                Select::make('coachedTeams')
                    ->multiple()
                    ->relationship(
                        name: 'coachedTeams', 
                        titleAttribute: 'name'
                    )
                ->maxItems(10)
            ]);
    }
}
