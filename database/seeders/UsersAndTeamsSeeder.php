<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use Database\Seeders\PermissionsAndRolesSeeder;

class UsersAndTeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createSuperUser();

        $testUsersByRoleCode = $this->createTestUsers();

        $this->createTestTeams($testUsersByRoleCode);
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

    private function createTestTeams(array $testUsersByRoleCode): void
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
                'coach_id' => $k % 2 === 0 ? $testUsersByRoleCode['c'][$k % 3] : null
            ]);
            
            // All users in the set (including captain) are team members
            foreach ($participantUserSet as $participantUser) {
                $participantUser->team()->associate($team);
            }
        }
    }
}
