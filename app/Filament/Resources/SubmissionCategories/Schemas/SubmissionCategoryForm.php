<?php

namespace App\Filament\Resources\SubmissionCategories\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;

use Filament\Schemas\Schema;

class SubmissionCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('subtitle')
                    ->required(),
                TextInput::make('points')
                    ->numeric()
                    ->required(),
                RichEditor::make('description')
                    ->columnSpan(2)
            ]);
    }
}
