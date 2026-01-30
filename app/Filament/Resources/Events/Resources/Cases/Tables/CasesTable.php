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
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): HtmlString => new HtmlString(self::sourceUrlFormatter($state))),
                TextColumn::make('submissions_count')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        default => 'info'
                    })
                    ->label('Qty Submissions')
                    ->sortable()
                    ->counts('submissions'),
                TextColumn::make('total_points')
                    ->label('Total Points')
                    ->numeric()
                    ->getStateUsing(function ($record) {
                        return $record->submissions()
                            ->with('category')
                            ->get()
                            ->sum(function ($submission) {
                                return $submission->category->points ?? 0;
                            });
                    })
                    ->sortable(query: function ($query, string $direction) {
                        return $query
                            ->withSum(['submissions as total_points' => function ($query) {
                                $query->join('submission_categories', 'submissions.submission_category_id', '=', 'submission_categories.id');
                            }], 'submission_categories.points')
                            ->orderBy('total_points', $direction);
                    })
                
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
