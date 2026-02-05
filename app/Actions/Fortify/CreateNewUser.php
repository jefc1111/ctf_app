<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\TicketPurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $validator = Validator::make($input, [
            'ticket_id' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],            
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ]);

        // There should really only be one (or none) but we'll fetch all just in case
        $ticketPurchases = TicketPurchase::where([
            'ticket_id' => $input['ticket_id'],
            'claimed' => false
        ])->get();

        $validator->after(function($validator) use ($ticketPurchases, $input) {
            if ($ticketPurchases->isEmpty()) {
                $claimedTicketExists = TicketPurchase::where([
                    'ticket_id' => $input['ticket_id'],
                    'claimed' => true
                ])->exists();
                
                \Log::warning("Registration attempt with email {$input['email']} tried to claim ticket id ".$input['ticket_id']
                ." which ".($claimedTicketExists ? "has already been claimed" : "does not match with any TicketPurchase"));
                
                $validator->errors()->add(
                    'ticket_id', 'The provided ticket ID is invalid'.($claimedTicketExists ? ' or has already been claimed.' : null)
                );
            }

            // This would be very surprising
            if ($ticketPurchases->count() > 1) {
                \Log::warning("User registered with ticket id ".$input['ticket_id']." which matches to multiple TicketPurchases");

                $validator->errors()->add(
                    'ticket_id', "There is a problem with the ticket id"
                );
            }
        });

        $validator->validate();
        
        // Now let's allocate the ticket purchase and create the user
        $ticketPurchase = TicketPurchase::firstWhere('ticket_id', $input['ticket_id']);

        return DB::transaction(function () use ($input, $ticketPurchase) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($ticketPurchase) {
                $user->assignRole('Participant');

                $ticketPurchase->allocate($user);

                //$this->createTeam($user);
            });
        });
    }

    // This relates to Laravel Jetstream's own teams implementation which we are ignoring in favour of our own
    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
