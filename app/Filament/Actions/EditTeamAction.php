<?php

namespace App\Filament\Actions;

use App\Models\Team;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class EditTeamAction extends Action
{
    protected ?Team $team;

    public function team(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public static function getDefaultName(): ?string
    {
        return 'editTeam';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Edit Team & View Join Code')
            ->icon('heroicon-m-pencil-square')
            ->modal()
            ->modalHeading('Edit Team')
            ->modalWidth('lg')
            ->modalSubmitActionLabel('Save Changes')
            ->form(function () {
                return [
                    TextInput::make('team_name')
                        ->default($this->team->name)
                        ->label('Team Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('join_code')
                        ->default($this->team->join_code)
                        ->label('Join Code')
                        ->readOnly()
                        ->copyable(copyMessage: 'Team join code copied', copyMessageDuration: 1500)
                        ->helperText('Send this code to someone to allow them to join your team.'),
                    Placeholder::make('members')
                        ->label("Members (maximum of 4)")
                        ->content(function () {
                            return new HtmlString(
                                $this->team->members->map(
                                    fn($member) =>
                                    "<div>{$member->name}</div>"
                                )->join('')
                            );
                        })
                ];
            })
            ->action(function (array $data): void {
                $this->team->update(['name' => $data['team_name']]);
            })
            ->after(function () {
                $this->getLivewire()->dispatch('refreshTable');
            });
    }
}