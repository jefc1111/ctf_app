<?php

namespace App\Filament\Resources\Audits;

use App\Filament\Resources\Audits\Pages\ListAudits;
use App\Filament\Resources\Audits\Schemas\AuditForm;
use Tapp\FilamentAuditing\Filament\Resources\Audits\Schemas\AuditInfolist;
use App\Filament\Resources\Audits\Tables\AuditsTable;
use OwenIt\Auditing\Models\Audit;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuditResource extends Resource
{
    protected static ?string $model = Audit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::PencilSquare;

    protected static string | UnitEnum | null $navigationGroup = 'Admin';

    protected static ?string $recordTitleAttribute = 'event';

    protected static ?int $navigationSort = 200;

    public static function form(Schema $schema): Schema
    {
        return AuditForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AuditInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditsTable::configure($table);
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
            'index' => ListAudits::route('/')
        ];
    }
}
