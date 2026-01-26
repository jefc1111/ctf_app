<?php

namespace App\Filament\Resources\Events\Resources\Cases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('age')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('missing_since')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                TextColumn::make('source_url')
                    ->formatStateUsing(fn (string $state): HtmlString => new HtmlString(self::sourceUrlFormatter($state)))
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

    private static function sourceUrlFormatter(string $url): string 
    {
        $linkText = Str::limit($url, 50, ' (...)');
         
        return "<a href='$url' target='_blank'>$linkText</a>";
    }
}
