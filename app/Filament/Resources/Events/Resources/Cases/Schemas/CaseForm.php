<?php

namespace App\Filament\Resources\Events\Resources\Cases\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;

class CaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),                
                TextInput::make('age'),
                Textarea::make('characteristics'),
                Textarea::make('disappearance_details'),
                TextInput::make('height'),
                TextInput::make('weight'),
                TextInput::make('missing_from')
                    ->belowContent('Last seen location.'),
                DateTimePicker::make('missing_since'),
                TextInput::make('missing_since_note'),
                TextInput::make('source_url'),
                Textarea::make('notes')
                    ->columnSpan(2)
                    ->rows(5)
                // DateTimePicker::make('start_time'),
                // DateTimePicker::make('end_time')
            ]);
    }
}
