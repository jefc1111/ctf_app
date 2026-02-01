<?php

namespace App\Filament\Resources\TicketPurchases\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

use Filament\Schemas\Schema;

class TicketPurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->required()
                    ->relationship(
                        name: 'event', 
                        titleAttribute: 'name'
                    ),
                TextInput::make('purchaser_email')
                    ->required(),
                TextInput::make('ticket_id')
                    ->required(),
                TextInput::make('discord_user'),

                Section::make('Claimant')
                    ->description('A registered Participant user that has claimed this purchase')
                    ->schema([
                        Toggle::make('claimed'),
                        Select::make('claimed_by_user_id')
                            ->required()
                            ->relationship(
                                name: 'claimedBy', 
                                titleAttribute: 'name'
                            ),
                        TextInput::make('claimed_at')
                    ])
                    ->collapsible()




                /*
                Select::make('submission_category_id')
                    ->required()
                    ->label('Submission category')
                    ->relationship(
                        name: 'category', 
                        titleAttribute: 'name'
                    )
                    ->getOptionLabelFromRecordUsing(fn (SubmissionCategory $record): ?string => $record->nameAndPoints()),
                Select::make('team_id')
                    ->required()
                    ->relationship(
                        name: 'team', 
                        titleAttribute: 'name'
                    ),
                Select::make('case_id')
                    ->required()
                    ->relationship(
                        name: 'case', 
                        titleAttribute: 'name'
                    ),
                Toggle::make('draft'),
                Textarea::make('content')
                    ->required()
                    ->columnSpan(2)
                    ->rows(3),
                Textarea::make('explanation')
                    ->columnSpan(2)
                    ->rows(3),                
                Select::make('decision_status')
                    ->required()
                    ->options(SubmissionDecisionStatus::toArray()),
                Textarea::make('decision_supporting_evidence')                    
                    ->rows(3),  
                    */              
            ]);
    }
}
