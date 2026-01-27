<?php

namespace App\Filament\Resources\SubmissionCategories;

use App\Filament\Resources\SubmissionCategories\Pages\CreateSubmissionCategory;
use App\Filament\Resources\SubmissionCategories\Pages\EditSubmissionCategory;
use App\Filament\Resources\SubmissionCategories\Pages\ListSubmissionCategories;
use App\Filament\Resources\SubmissionCategories\Schemas\SubmissionCategoryForm;
use App\Filament\Resources\SubmissionCategories\Tables\SubmissionCategoriesTable;
use App\Models\SubmissionCategory;
use BackedEnum;
use UnitEnum;
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

    protected static string|UnitEnum|null $navigationGroup = 'CTF';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return SubmissionCategoryForm::configure($schema);
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
            'create' => CreateSubmissionCategory::route('/create'),
            'edit' => EditSubmissionCategory::route('/{record}/edit'),
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
