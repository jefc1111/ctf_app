<?php

namespace App\Filament\Actions;

use App\Models\TicketPurchase;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class LeaveTeamAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'leaveTeam';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $user = auth()->user();

        $this
            ->label('Leave Team')
            ->icon('heroicon-o-arrow-right-on-rectangle')
            ->color('danger')
            ->visible(fn (TicketPurchase $record): bool =>
                $user->inTeamForTicketPurchase($record) &&
                !$user->isCaptain()
            )
            ->requiresConfirmation()
            ->modalHeading('Leave Team')
            ->modalDescription('Are you sure you want to leave your team? You will need to join or create a new team to participate in the event.')
            ->modalSubmitActionLabel('Leave Team')
            ->action(function (TicketPurchase $record) use ($user): void {
                $user->team_id = null;
                
                $user->save();
                
                Notification::make()
                    ->title('You have left the team')
                    ->success()
                    ->send();
            })
            ->after(function () {
                $this->getLivewire()->dispatch('refreshTable');
            });
    }
}