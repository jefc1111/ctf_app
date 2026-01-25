<?php

namespace App\Filament\Resources\Events\Resources\Cases;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Events\Resources\Cases\Pages\CreateCase;
use App\Filament\Resources\Events\Resources\Cases\Pages\EditCase;
use App\Filament\Resources\Events\Resources\Cases\Pages\ViewCase;
use App\Filament\Resources\Events\Resources\Cases\Schemas\CaseForm;
use App\Filament\Resources\Events\Resources\Cases\Schemas\CaseInfolist;
use App\Filament\Resources\Events\Resources\Cases\Tables\CasesTable;
use App\Models\CaseModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CaseResource extends Resource
{
    protected static ?string $model = CaseModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentDuplicate;

    protected static ?string $parentResource = EventResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CasesTable::configure($table);
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
            'create' => CreateCase::route('/create'),
            'view' => ViewCase::route('/{record}'),
            'edit' => EditCase::route('/{record}/edit'),
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
