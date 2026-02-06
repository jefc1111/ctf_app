<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TicketPurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->required(),
                TextInput::make('purchaser_email')
                    ->email()
                    ->required(),
                TextInput::make('ticket_id')
                    ->required(),
                TextInput::make('discord_user')
                    ->required(),
                Toggle::make('claimed')
                    ->required(),
                TextInput::make('claimed_by_user_id')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('claimed_at'),
            ]);
    }
}
