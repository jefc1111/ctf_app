<?php

namespace App\Filament\Resources\Submissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class SubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('content')
                    ->searchable()
                    ->limit(32),
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('decision_status')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('draft')
                    ->boolean()
                    // ->trueIcon(Heroicon::PauseCircle)
                    // ->falseIcon(Heroicon::PlayCircle)
                    ->trueColor('warning')
                    ->falseColor('gray'),
                TextColumn::make('decision_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING', 'UNDER_REVIEW', 'AWAITING_PEER_REVIEW',  => 'warning',
                        'APPROVED' => 'success',
                        'DECLINED' => 'danger',
                        default => 'gray'
                    }),
                TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                TextColumn::make('team.event.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('team.name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
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
