<?php

namespace App\Filament\Resources\SubmissionCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;

class SubmissionCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('name')
                        ->searchable()
                        ->sortable()
                        ->weight(FontWeight::Bold)
                        ->description('Category Name', position: 'above'),
                    TextColumn::make('subtitle')
                        ->searchable()
                        ->sortable()
                        ->description('Subtitle', position: 'above'),
                    TextColumn::make('points')
                        ->searchable()
                        ->sortable()
                        ->description('Points Per Submission', position: 'above'),
                    TextColumn::make('submissions_count')
                        ->badge()
                        ->color(fn(string $state): string => match ($state) {
                            default => 'gray',
                        })
                        ->label('Qty Submissions')
                        ->sortable()
                        ->counts('submissions')
                        ->description('Total Sumissions Count', position: 'above'),
                ]),
                Panel::make([
                    Stack::make([
                        TextColumn::make('description')
                            ->html()
                    ]),
                ])->collapsible(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
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
