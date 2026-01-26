<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditCoachUser;
use App\Filament\Resources\Users\Pages\ListCoachUsers;
use App\Filament\Resources\Users\Schemas\CoachUserForm;
use App\Filament\Resources\Users\Tables\CoachUsersTable;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoachUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Truck;

    protected static string | UnitEnum | null $navigationGroup = 'CTF';

    protected static ?string $navigationLabel = 'Coaches';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'coaches';

    public static function form(Schema $schema): Schema
    {
        return CoachUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {        
        return CoachUsersTable::configure($table);
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
            'index' => ListCoachUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditCoachUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::role('Coach')->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('Coach');
    }
}
