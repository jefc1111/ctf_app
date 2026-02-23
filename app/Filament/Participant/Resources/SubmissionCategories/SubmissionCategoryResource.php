<?php

namespace App\Filament\Participant\Resources\SubmissionCategories;

use App\Filament\Participant\Resources\SubmissionCategories\Pages\ListSubmissionCategories;
use App\Filament\Participant\Resources\SubmissionCategories\Schemas\SubmissionCategoryInfolist;
use App\Filament\Participant\Resources\SubmissionCategories\Tables\SubmissionCategoriesTable;
use App\Models\SubmissionCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubmissionCategoryResource extends Resource
{
    protected static ?string $model = SubmissionCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;

    protected static ?int $navigationSort = 50;

    public static function infolist(Schema $schema): Schema
    {
        return SubmissionCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubmissionCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubmissionCategories::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
