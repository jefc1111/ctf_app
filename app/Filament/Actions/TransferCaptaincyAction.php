<?php

namespace App\Filament\Actions;

use App\Models\TicketPurchase;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;

class TransferCaptaincyAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'transferCaptaincy';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $user = auth()->user();
        $team = $user->team;
        $isSoleMemember = $team->members->count() === 1;

        $this
            ->label($isSoleMemember ? 'Surrender Captaincy' : 'Transfer Captaincy')
            ->icon('heroicon-o-arrows-right-left')
            ->form(function () use ($user, $team, $isSoleMemember): array {
                if ($isSoleMemember) {
                    return [];
                }

                $options = $team->members
                    ->mapWithKeys(fn ($member) => [
                        $member->id => $member->id === $user->id
                            ? "{$member->name} (You)"
                            : $member->name,
                    ])
                    ->toArray();

                return [
                    Radio::make('new_captain_id')
                        ->label('Select new captain')
                        ->options($options)
                        ->disableOptionWhen(fn (string $value): bool => (int) $value === $user->id)
                        ->required(),
                ];
            })
            ->action(function (TicketPurchase $record, array $data) use ($team, $isSoleMemember): void {
                if ($isSoleMemember) {
                    $team->captain_id = null;    
                } else {
                    $newCaptain = $team->members()->findOrFail($data['new_captain_id']);

                    $team->captain_id = $newCaptain->id;
                }

                $team->save();
                
                Notification::make()
                    ->title('Captaincy transferred successfully')
                    ->success()
                    ->send();
            })
            ->modalHeading('Transfer Captaincy')
            ->modalDescription($isSoleMemember ? 'You are the only team member.' : 'Choose a team member to become the new captain.')
            ->modalSubmitActionLabel($isSoleMemember ? 'Surrender Captaincy' : 'Transfer Captaincy');
    }
}