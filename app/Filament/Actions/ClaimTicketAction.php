<?php

namespace App\Filament\Actions;

use App\Models\TicketPurchase;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ClaimTicketAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'claimTicket';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Claim Ticket')
            ->icon('heroicon-o-ticket')
            ->modal()
            ->form([
                TextInput::make('ticket_id')
                    ->label('Ticket ID')
                    ->placeholder('Enter your ticket ID')
                    ->required()
                    ->uuid()
                    ->helperText('Paste the ID from your ticket purchase confirmation'),
            ])
            ->action(function (array $data, Action $action): void {
                $user = Auth::user();
                $ticketId = $data['ticket_id'];
                
                // Tier 2: Check if user has already claimed THIS specific ticket
                $alreadyClaimedByUser = TicketPurchase::where('ticket_id', $ticketId)
                    ->where('claimed_by_user_id', $user->id)
                    ->where('claimed', true)
                    ->first();

                if ($alreadyClaimedByUser) {
                    Notification::make()
                        ->warning()
                        ->title('Already Claimed')
                        ->body('You have already claimed this ticket.')
                        ->send();
                    
                    $action->halt();
                    return;
                }

                // Find ticket with matching ID (claimed or unclaimed)
                $ticketPurchase = TicketPurchase::where('ticket_id', $ticketId)->first();

                // Tier 4: No ticket found with this ID at all
                if (!$ticketPurchase) {
                    Notification::make()
                        ->danger()
                        ->title('Invalid Ticket')
                        ->body('No ticket found with this ID. Please check the ID and try again.')
                        ->send();
                    
                    $action->halt();
                    return;
                }

                // Tier 3: Check if user has already claimed a different ticket for this event
                $existingClaimForEvent = TicketPurchase::where('event_id', $ticketPurchase->event_id)
                    ->where('claimed_by_user_id', $user->id)
                    ->where('claimed', true)
                    ->where('ticket_id', '!=', $ticketId) // Different ticket
                    ->with('event') // Load event relationship
                    ->first();

                if ($existingClaimForEvent) {
                    $eventName = $ticketPurchase->event->name ?? 'this event';
                    
                    Notification::make()
                        ->warning()
                        ->title('Event Ticket Already Claimed')
                        ->body("You have already claimed a ticket for {$eventName}. Only one ticket per event can be claimed per user.")
                        ->send();
                    
                    $action->halt();
                    return;
                }

                // Check if ticket is still unclaimed
                if ($ticketPurchase->claimed) {
                    Notification::make()
                        ->warning()
                        ->title('Ticket Already Claimed')
                        ->body('This ticket has already been claimed by another user.')
                        ->send();
                    
                    $action->halt();
                    return;
                }

                // All validations passed - claim the ticket
                $ticketPurchase->update([
                    'claimed' => true,
                    'claimed_by_user_id' => $user->id,
                    'claimed_at' => now(),
                ]);

                Notification::make()
                    ->success()
                    ->title('Ticket Claimed Successfully')
                    ->body("You've successfully claimed the ticket for {$ticketPurchase->purchaser_email}")
                    ->send();
                
                $action->success();
            });
    }
}