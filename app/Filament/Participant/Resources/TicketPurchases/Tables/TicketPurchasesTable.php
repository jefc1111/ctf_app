<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\TicketPurchase;
use App\Filament\Actions\ReleaseTicketClaimAction;
use App\Filament\Actions\JoinOrCreateTeamAction;
use App\Filament\Actions\TransferCaptaincyAction;
use App\Filament\Actions\LeaveTeamAction;
use App\Filament\Actions\EditTeamAction;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;

class TicketPurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name'),
                TextColumn::make('purchaser_email'),
                TextColumn::make('ticket_id'),
                TextColumn::make('discord_user'),
                TextColumn::make('claimed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('progress_status')
                    ->label('Event Status')
                    ->badge()
                    ->state(fn (TicketPurchase $ticketPurchase): string => $ticketPurchase->event->progressStatusText())
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'In Progress' => 'success',
                        'Complete' => 'gray',
                    }),
                TextColumn::make('team_status')
                    ->label('Team Status')
                    ->state(function (TicketPurchase $ticketPurchase): string {
                        $user = auth()->user();

                        // Only show for tickets claimed by current user
                        if ($ticketPurchase->claimedBy->id !== $user->id) {
                            return '—';
                        }

                        // Check if user has a team for this event
                        if ($user->inTeamForTicketPurchase($ticketPurchase)) {
                            $teamName = $user->team->name;

                            return $user->isCaptain() ? "⭐ {$teamName} (Captain)" : "✓ {$teamName}";
                        }

                        return 'No Team';
                    })
                    // ->badge()
                    ->color(function (TicketPurchase $ticketPurchase): string {
                        $user = auth()->user();

                        if ($ticketPurchase->claimedBy->id !== $user->id) {
                            return 'gray';
                        }

                        if ($user->inTeamForTicketPurchase($ticketPurchase)) {       
                            return $user->isCaptain() ? 'success' : 'info';
                        }

                        return 'warning';
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    // Show if ticket purchase is for current user, and user not already in a team for the ticket purchase's event
                    JoinOrCreateTeamAction::make()
                        ->disabled(fn(TicketPurchase $tp): bool => auth()->user()->inTeamForTicketPurchase($tp)),
                    EditTeamAction::make()->team(auth()->user()->team)
                        ->visible(fn(TicketPurchase $tp): bool => auth()->user()->isCaptain()),                  
                    // Show if user is captain of a team for this event
                    TransferCaptaincyAction::make()
                        ->disabled(fn(TicketPurchase $tp): bool => ! auth()->user()->isCaptain()),
                    // Show if user is in a team for the event, but not captain
                    LeaveTeamAction::make()
                        ->disabled(fn(TicketPurchase $tp): bool => ! auth()->user()->inTeamForTicketPurchase($tp) || auth()->user()->isCaptain())
                        ->tooltip(function(TicketPurchase $tp): string {
                            return auth()->user()->isCaptain()
                            ? "You must transfer captaincy first"
                            : "";
                        }),
                    // Show if user is not in any team for this event
                    ReleaseTicketClaimAction::make()
                        ->disabled(fn(TicketPurchase $tp): bool => auth()->user()->inTeamForTicketPurchase($tp))
                        ->tooltip(function(TicketPurchase $tp): string {
                            return auth()->user()->inTeamForTicketPurchase($tp)
                            ? "You must leave your team first"
                            : "";
                        })
                ])
                ->size(Size::Small)
                ->dropdownWidth(Width::ExtraSmall)
                ->label('Team Actions')
                ->button()
            ])
            ->filters([
                //
            ]);
    }
}
