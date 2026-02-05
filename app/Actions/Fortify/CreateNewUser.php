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
        Validator::make($input, [
            'ticket_id' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],            
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // There should really only be one (or none) but we'll fetch all just in case
        $ticketPurchases = TicketPurchase::where([
            'ticket_id' => $input['ticket_id'],
            'claimed' => false
        ])->get();

        if ($ticketPurchases->isEmpty()) {
            \Log::warning("User registered with ticket id ".$input['ticket_id']." which does not match with any unclaimed TicketPurchase");

            abort(403, "Invalid ticket id");
        }

        // This would be very surprising
        if ($ticketPurchases->count() > 1) {
            \Log::warning("User registered with ticket id ".$input['ticket_id']." which matches to multiple TicketPurchases");

            abort(403, "There is a problem with the ticket id");
        }
        
        if ($ticketPurchases->count() === 1) {
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
        } else {
            abort(403, "Unexpected error with ticket id");
        }
    }

    // This relates to Laravel Jetstream's own teams implementation which we are inoring in favour of our own
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
