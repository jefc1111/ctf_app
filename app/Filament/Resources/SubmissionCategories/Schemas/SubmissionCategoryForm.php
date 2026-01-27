<?php

namespace App\Filament\Resources\SubmissionCategories\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

use Filament\Schemas\Schema;

class SubmissionCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('points')
                    ->required(),
                Textarea::make('description')
                    ->columnSpan(2)
                    ->rows(5)
            ]);
    }
}
