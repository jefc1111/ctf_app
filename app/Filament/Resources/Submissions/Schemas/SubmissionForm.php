<?php

namespace App\Filament\Resources\Submissions\Schemas;

use App\Enums\SubmissionDecisionStatus;
use App\Models\SubmissionCategory;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

use Filament\Schemas\Schema;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('submission_category_id')
                    ->required()
                    ->label('Submission category')
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'name'
                    )
                    ->getOptionLabelFromRecordUsing(fn(SubmissionCategory $record): ?string => $record->nameAndPoints()),
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
                Section::make('Coach decision')
                    ->description('The status of the decision and the reasoning behind it (if applicable)')
                    ->schema([
                        Select::make('decision_status')
                            ->required()
                            ->default('PENDING')
                            ->options(SubmissionDecisionStatus::toArray()),
                        Textarea::make('decision_supporting_evidence')
                            ->rows(3),
                    ])
                    ->collapsible(),
                Select::make('owner_id')
                    ->default(auth()->user()->id)
                    ->required()
                    ->relationship(
                        name: 'owner',
                        titleAttribute: 'name'
                    ),
            ]);
    }
}
