<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Models\Event;
use Filament\Tables\Columns\Summarizers\Count;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->sortable(),
                TextColumn::make('progress_status')
                    ->badge()
                    ->state(fn (Event $record): string => $record->progressStatusText())
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'In Progress' => 'success',
                        'Complete' => 'gray',
                    }),
                TextColumn::make('cases_count')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '4' => 'success',
                        '0' => 'danger',
                        default => 'warning',
                    })
                    ->label('Qty Cases')
                    ->sortable()
                    ->counts('cases'),
                TextColumn::make('teams_count')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        default => 'warning',
                    })
                    ->label('Qty Teams')
                    ->sortable()
                    ->counts('teams'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
