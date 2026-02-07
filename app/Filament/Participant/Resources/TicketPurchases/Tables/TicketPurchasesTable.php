<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Actions\ReleaseTicketClaimAction;

class TicketPurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name'),
                TextColumn::make('purchaser_email'),
                TextColumn::make('ticket_id'),
                TextColumn::make('discord_user'),
                TextColumn::make('claimed_at')
                    ->dateTime()
                    ->sortable(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ReleaseTicketClaimAction::make(),                
            ])
            ->filters([
                //
            ]);
    }
}
