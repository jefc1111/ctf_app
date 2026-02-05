<?php

namespace App\Filament\Resources\Audits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Tapp\FilamentAuditing\Filament\Tables\Columns\AuditValuesColumn;
use Filament\Tables\Columns\Column;;
use Illuminate\Database\Eloquent\Model;

class AuditsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Username'),
                TextColumn::make('user.email')
                    ->searchable()                
                    ->label('Email'),
                TextColumn::make('event'),
                // TextColumn::make('created_at')
                //     ->since(),
                TextColumn::make('created_at')
                // AuditValuesColumn::make('old_values')
                //     ->searchable(),
                // AuditValuesColumn::make('new_values')
                //     ->searchable(),
                // IconColumn::make('is_featured')
                //     ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
