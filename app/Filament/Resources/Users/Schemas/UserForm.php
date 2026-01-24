<?php

namespace App\Filament\Resources\Users\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Operation;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserForm
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
                TextInput::make('password')
                    ->required()
                    ->hiddenOn(Operation::Edit),
                Select::make('roles')
                    ->multiple(false)
                    ->relationship(titleAttribute: 'name')
                    ->required()
                    ->preload()
            ]);
    }
}
