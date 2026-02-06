<?php

namespace App\Filament\Participant\Resources\TicketPurchases;

use App\Filament\Participant\Resources\TicketPurchases\Pages\CreateTicketPurchase;
use App\Filament\Participant\Resources\TicketPurchases\Pages\EditTicketPurchase;
use App\Filament\Participant\Resources\TicketPurchases\Pages\ListTicketPurchases;
use App\Filament\Participant\Resources\TicketPurchases\Schemas\TicketPurchaseForm;
use App\Filament\Participant\Resources\TicketPurchases\Tables\TicketPurchasesTable;
use App\Models\TicketPurchase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketPurchaseResource extends Resource
{
    protected static ?string $model = TicketPurchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Ticket;

    protected static ?string $recordTitleAttribute = 'purchaser_email';

    public static function form(Schema $schema): Schema
    {
        return TicketPurchaseForm::configure($schema);
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
            'edit' => EditTicketPurchase::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()->where('claimed_by_user_id', $user->id);
    }
}
