<?php

namespace App\Filament\Actions;

use App\Models\CaseModel;
use App\Models\Submission;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Models\SubmissionCategory;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

class CreateSubmissionAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'createSubmission';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $userIsInTeam = !! auth()->user()->team_id;

        if (! $userIsInTeam) {
            $this
                ->tooltip('You must be in a team to be able to submit a flag')
                ->disabled();
        }

        $this                        
            ->label('Submit Flag')
            ->icon('heroicon-o-flag')
            ->color('primary')
            ->form([
                TextInput::make('name')
                    ->required(),
                Select::make('submission_category_id')
                    ->required()
                    ->label('Submission category')
                    ->options(
                        SubmissionCategory::all()
                            ->mapWithKeys(fn(SubmissionCategory $category) => [
                                $category->id => $category->nameAndPoints()
                            ])
                    ),
                Toggle::make('draft')
                    ->belowLabel(
                        'Draft submissions are not be visible to coaches. Make sure your submission is taken out of draft when ready for the coach decision.'
                    )
                    ->default(false),
                Textarea::make('content')
                    ->required()
                    ->columnSpan(2)
                    ->rows(3),
                Textarea::make('explanation')
                    ->columnSpan(2)
                    ->rows(3),
            ])
            ->action(function (CaseModel $record, array $data) {
                Submission::create([
                    'name' => $data['name'],
                    'case_id' => $record->id,                    
                    'owner_id' => auth()->id(),
                    'team_id' => auth()->user()->team_id,
                    'event_id' => $record->event->id,
                    'submission_category_id' => $data['submission_category_id'],
                    'content' => $data['content'],
                    'explanation' => $data['explanation'],
                    'draft' => $data['draft'],
                    'decision_status' => 'PENDING',
                ]);

                // Optionally show a success notification
                Notification::make()
                    ->title('Submission created successfully')
                    ->success()
                    ->send();
            });
    }
}