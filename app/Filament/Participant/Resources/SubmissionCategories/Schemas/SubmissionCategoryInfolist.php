<?php

namespace App\Filament\Participant\Resources\SubmissionCategories\Schemas;

use App\Models\SubmissionCategory;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubmissionCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('subtitle')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->html()
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('points')
                    ->label('Points awarded per accepted submission')
                    ->numeric(),
            ]);
    }
}
