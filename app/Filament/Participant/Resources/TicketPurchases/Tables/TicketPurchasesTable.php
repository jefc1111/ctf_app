<?php

namespace App\Filament\Participant\Resources\TicketPurchases\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\TicketPurchase;
use App\Filament\Actions\ReleaseTicketClaimAction;
use App\Filament\Actions\JoinOrCreateTeamAction;

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
                TextColumn::make('team_status')
                    ->label('Team Status')
                    ->state(function (TicketPurchase $record): string {
                        $user = auth()->user();
                        // \Log::error($record);
                        \Log::error($user->team);
                        // Only show for tickets claimed by current user
                        if ($record->claimed_by_user_id !== $user->id) {
                            return '—';
                        }

                        // Check if user has a team for this event
                        if ($user->team && $user->team->event_id === $record->event_id) {
                            $teamName = $user->team->name;
                            $isCaptain = $user->captainedTeam && $user->captainedTeam->id === $user->team->id;

                            return $isCaptain ? "⭐ {$teamName} (Captain)" : "✓ {$teamName}";
                        }

                        return 'No Team';
                    })
                    ->badge()
                    ->color(function (TicketPurchase $record): string {
                        $user = auth()->user();

                        if ($record->claimed_by_user_id !== $user->id) {
                            return 'gray';
                        }

                        if ($user->team && $user->team->event_id === $record->event_id) {
                            $isCaptain = $user->captainedTeam && $user->captainedTeam->id === $user->team->id;
                            return $isCaptain ? 'success' : 'info';
                        }

                        return 'warning';
                    }),

                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                JoinOrCreateTeamAction::make()
                    ->visible(function (TicketPurchase $record): bool {
                        $user = auth()->user();

                        // Only show if ticket is claimed by current user
                        if ($record->claimed_by_user_id !== $user->id) {
                            return false;
                        }

                        // Only show if user doesn't have a team for this event
                        return !($user->team && $user->team->event_id === $record->event_id);
                    }),
                ReleaseTicketClaimAction::make(),
            ])
            ->filters([
                //
            ]);
    }
}
