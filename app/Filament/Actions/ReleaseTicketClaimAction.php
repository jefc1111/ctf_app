<?php

namespace App\Filament\Actions;

use App\Models\TicketPurchase;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ReleaseTicketClaimAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'releaseTicketClaim';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Release Ticket Claim')
            ->icon('heroicon-o-document-minus')
            ->requiresConfirmation()
            ->modalHeading('Release Ticket Claim')
            ->modalDescription('Are you sure you want to release your claim on this ticket? You will be able to claim another ticket for this event afterwards.')
            ->modalSubmitActionLabel('Yes, Release Claim')
            ->visible(fn (TicketPurchase $record): bool => 
                $record->claimed && 
                $record->claimed_by_user_id === Auth::id()
            )
            ->action(function (TicketPurchase $record): void {
                $user = Auth::user();
                
                // Double-check the user owns this claim
                if ($record->claimed_by_user_id !== $user->id) {
                    Notification::make()
                        ->danger()
                        ->title('Unauthorized')
                        ->body('You can only release your own ticket claims.')
                        ->send();
                    
                    return;
                }

                // Release the claim
                $record->update([
                    'claimed' => false,
                    'claimed_by_user_id' => null,
                    'claimed_at' => null,
                ]);

                Notification::make()
                    ->success()
                    ->title('Claim Released')
                    ->body('You have successfully released your claim on this ticket.')
                    ->send();
            });
    }
}