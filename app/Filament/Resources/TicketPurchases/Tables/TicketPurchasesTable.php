<?php

namespace App\Filament\Resources\TicketPurchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Icons\Heroicon;

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
                IconColumn::make('claimed')
                    ->boolean()
                    // ->trueIcon(Heroicon::PauseCircle)
                    ->falseIcon(Heroicon::MinusCircle)
                    ->trueColor('success')
                    ->falseColor('gray'),
                TextColumn::make('claimed_by_user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('claimed_at')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
