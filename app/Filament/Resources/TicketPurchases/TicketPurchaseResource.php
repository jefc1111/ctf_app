<?php

namespace App\Filament\Resources\TicketPurchases;

use App\Filament\Resources\TicketPurchases\Pages\CreateTicketPurchase;
use App\Filament\Resources\TicketPurchases\Pages\EditTicketPurchase;
use App\Filament\Resources\TicketPurchases\Pages\ListTicketPurchases;
use App\Filament\Resources\TicketPurchases\Pages\ViewTicketPurchase;
use App\Filament\Resources\TicketPurchases\Schemas\TicketPurchaseForm;
use App\Filament\Resources\TicketPurchases\Schemas\TicketPurchaseInfolist;
use App\Filament\Resources\TicketPurchases\Tables\TicketPurchasesTable;
use App\Models\TicketPurchase;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TicketPurchaseResource extends Resource
{
    protected static ?string $model = TicketPurchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'purchaser_email';

    protected static string|UnitEnum|null $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 100;

    public static function form(Schema $schema): Schema
    {
        return TicketPurchaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TicketPurchaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketPurchasesTable::configure($table);
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
            'index' => ListTicketPurchases::route('/'),
            'create' => CreateTicketPurchase::route('/create'),
            'view' => ViewTicketPurchase::route('/{record}'),
            'edit' => EditTicketPurchase::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
