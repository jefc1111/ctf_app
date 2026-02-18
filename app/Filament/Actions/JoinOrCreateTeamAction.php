<?php

namespace App\Filament\Actions;

use App\Models\Team;
use App\Models\User;
use App\Models\TicketPurchase;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Utilities\Get;

class JoinOrCreateTeamAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'joinOrCreateTeam';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Join / Create Team')
            ->icon('heroicon-o-user-group')            
            ->modal()
            ->modalHeading('Join or Create a Team')
            ->modalWidth('lg')
            ->form(function (TicketPurchase $record) {
                return [
                    Radio::make('action_type')
                        ->label('What would you like to do?')
                        ->options([
                            'create' => 'Create a new team',
                            'join' => 'Join an existing team',
                        ])
                        ->required()
                        ->reactive()
                        ->default('create'),

                    TextInput::make('team_name')
                        ->label('Team Name')
                        ->required()
                        ->maxLength(255)
                        ->visible(fn (Get $get) => $get('action_type') === 'create')
                        ->helperText('You will become the team captain'),

                    TextInput::make('join_code')
                        ->label('Join Code')
                        ->required()
                        ->maxLength(255)
                        ->visible(fn (Get $get) => $get('action_type') === 'join')
                        ->helperText('Enter the join code provided by your team captain'),

                    Placeholder::make('event_info')
                        ->label('Event')
                        ->content($record->event->name ?? 'Unknown Event'),
                ];
            })
            ->action(function (array $data, Action $action, TicketPurchase $record): void {
                $user = Auth::user();
                $eventId = $record->event_id;

                // Check if user is already in a team for this event
                if ($user->team && $user->team->event_id === $eventId) {
                    Notification::make()
                        ->warning()
                        ->title('Already in Team')
                        ->body('You are already part of a team for this event.')
                        ->send();
                    
                    $action->halt();
                    return;
                }

                if ($data['action_type'] === 'create') {
                    $this->createTeam($user, $eventId, $data['team_name'], $action);
                } else {
                    $this->joinTeam($user, $eventId, $data['join_code'], $action);
                }
            });
    }

    protected function createTeam(
        User $user, 
        int $eventId,
        string $teamName, 
        Action $action
    ): void
    {
        // Check if user is already a captain of another team
        if ($user->captainedTeam) {
            Notification::make()
                ->danger()
                ->title('Already a Captain')
                ->body('You are already the captain of another team. Please leave that team first.')
                ->send();
            
            $action->halt();
            return;
        }

        // Create the team
        $team = Team::create([
            'name' => $teamName,
            'event_id' => $eventId,
            'captain_id' => $user->id
        ]);

        // Associate user with team
        $user->update(['team_id' => $team->id]);

        Notification::make()
            ->success()
            ->title('Team Created')
            ->body("Team '{$teamName}' created successfully! Share your join code: {$team->join_code}")
            ->send();
        
        $action->success();
    }

    protected function joinTeam(
        User $user, 
        int $eventId, 
        string $joinCode, 
        Action $action
    ): void
    {
        // Find team by join code
        $team = Team::where('join_code', $joinCode)
            ->where('event_id', $eventId)
            ->withCount('members')
            ->first();

        if (!$team) {
            Notification::make()
                ->danger()
                ->title('Invalid Join Code')
                ->body('No team found with this join code for this event.')
                ->send();
            
            $action->halt();
            return;
        }

        // Check if team is full
        if ($team->members_count >= 4) {
            Notification::make()
                ->warning()
                ->title('Team Full')
                ->body('This team already has 4 members and cannot accept more players.')
                ->send();
            
            $action->halt();
            return;
        }

        // Join the team
        $user->update(['team_id' => $team->id]);

        Notification::make()
            ->success()
            ->title('Joined Team')
            ->body("You have successfully joined team '{$team->name}'!")
            ->send();
        
        $action->success();
    }
}