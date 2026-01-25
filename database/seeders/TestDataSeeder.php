<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use App\Models\Event;
use App\Models\CaseModel;
use Database\Seeders\PermissionsAndRolesSeeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createSuperUser();

        $events = $this->createTestEventsAndCases();

        $testUsersByRoleCode = $this->createTestUsers();

        $this->createTestTeams($testUsersByRoleCode, $events);
    }

    private function createSuperUser(): void
    {
        $password = env('INITIAL_ADMIN_PASSWORD');

        if (! $password) {
            abort(400, "No INITIAL_ADMIN_PASSWORD set in .env");
        }

        $user = User::factory()->create([
            'name'     => 'Geoff',
            'email'    => 'geoff.c+admin@tracelabs.org',
            'password' => bcrypt($password),
        ]);
 
        $user->assignRole('Super Admin');
    }

    private function createTestUsers(): array
    {
        $password = env('TEST_USER_PASSWORD');

        if (! $password) {
            abort(400, "No TEST_USER_PASSWORD set in .env");
        }

        $qtyUsersPerRole = 20;

        $testUsersByRoleCode = [];

        foreach (PermissionsAndRolesSeeder::nonSuperAdminRoles() as $roleCode => $roleName) {
            $testUsersByRoleCode[$roleCode] = [];

            foreach (range(1, $qtyUsersPerRole) as $_i) {
                $user = User::factory()->create([
                    'name'     => fake()->name()." ($roleCode)",
                    'password' => bcrypt($password),
                ]);
        
                $user->assignRole($roleName);

                $testUsersByRoleCode[$roleCode][] = $user;
            }
        }

        return $testUsersByRoleCode;
    }

    private function createTestTeams(array $testUsersByRoleCode, array $events): void
    {
        if (! array_key_exists('p', $testUsersByRoleCode) || empty($testUsersByRoleCode['p'])) {
            \Log::warning("Unable to create test teams because no participant users are available");
        }
        
        // For each set of 4 participants we'll create a team (the last team may have < 4 members)
        foreach (collect($testUsersByRoleCode['p'])->chunk(4) as $k => $participantUserSet) {
            // The first participant in the set gets to be captain
            $team = Team::factory()->create([
                'name' => fake()->word(),
                'captain_id' => $participantUserSet->first(),
                // Let's assign a coach to every other team, and we'll re-use coaches
                'coach_id' => $k % 2 === 0 ? $testUsersByRoleCode['c'][$k % 3] : null,
                'event_id' => ! empty($events) ? $events[0]->id : null
            ]);
            
            // All users in the set (including captain) are team members
            foreach ($participantUserSet as $participantUser) {
                $participantUser->team()->associate($team);

                $participantUser->save();
            }
        }
    }

    private function createTestEventsAndCases(): array
    {
        $events = [];

        $qtyTestEvents = 5;

        $maxQtyCasesPerEvent = 4;

        foreach (range(1, $qtyTestEvents) as $i) {
            $event = Event::factory()->create();

            // Make sure the first event created gets a full set of cases, the others get randomised amounts of cases
            $qtyCasesForThisEvent = $i === 0 ? $maxQtyCasesPerEvent : rand(0, $maxQtyCasesPerEvent);
            
            foreach (range(1, $qtyCasesForThisEvent) as $_i) {
                CaseModel::factory()->create([
                    'event_id' => $event->id
                ]);
            }

            $events[] = $event;
        }

        return $events;
    }
}
