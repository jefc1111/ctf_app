<?php

namespace Database\Seeders;

use App\Models\SubmissionCategory;
use App\Models\Submission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use App\Models\Event;
use App\Models\CaseModel;
use App\Models\TicketPurchase;
use Database\Seeders\PermissionsAndRolesSeeder;
use Carbon\Carbon;
use Illuminate\Support\Arr;

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

        $teams = $this->createTestTeams($testUsersByRoleCode, $events);

        $this->createTestSubmissions($teams);

        $this->createTestTicketPurchases($events, $testUsersByRoleCode['p']);
    }

    private function createSuperUser(): void
    {
        $this->command->info("Creating Super Admins");

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

        $qtyUsersPerRole = [
            'a' => 4,
            'es' => 8,
            'sc' => 10,
            'c' => 25, 
            'p' => env('QTY_TEST_PARTICIPANTS', 1002)
        ];

        $defaultQty = 23;

        $testUsersByRoleCode = [];

        $total = array_sum($qtyUsersPerRole) 
        + ($defaultQty * (\count(PermissionsAndRolesSeeder::nonSuperAdminRoles()) - \count($qtyUsersPerRole)));
        
        $bar = $this->command->getOutput()->createProgressBar($total);
        
        $bar->start();
        
        foreach (PermissionsAndRolesSeeder::nonSuperAdminRoles() as $roleCode => $roleName) {
            $testUsersByRoleCode[$roleCode] = [];

            $qtyToCreateForRole = array_key_exists($roleCode, $qtyUsersPerRole) 
            ? $qtyUsersPerRole[$roleCode] 
            : $defaultQty;
            
            $this->command->newLine();
            $this->command->info("Creating $qtyToCreateForRole Users for role '$roleName'");
            $this->command->newLine();
            
            foreach (range(1, $qtyToCreateForRole) as $_i) {
                $user = User::factory()->create([
                    'name'     => fake()->name()." ($roleCode)",
                    'password' => bcrypt($password),
                ]);
        
                $user->assignRole($roleName);

                $testUsersByRoleCode[$roleCode][] = $user;

                $bar->advance();
            }
        }

        $bar->finish();

        $this->command->newLine();
        
        return $testUsersByRoleCode;
    }

    private function createTestTeams(array $testUsersByRoleCode, array $events): array
    {
        $this->command->info("Creating some test Teams");

        if (! array_key_exists('p', $testUsersByRoleCode) || empty($testUsersByRoleCode['p'])) {
            \Log::warning("Unable to create test teams because no participant users are available");
        }
        
        $teams = [];

        // For each set of 4 participants we'll create a team (the last team may have < 4 members)
        foreach (collect($testUsersByRoleCode['p'])->chunk(4) as $k => $participantUserSet) {
            // The first participant in the set gets to be captain
            $team = Team::factory()->create([
                'name' => fake()->word(),
                'captain_id' => $participantUserSet->first(),
                // Let's assign a coach to every other team (we'll re-use coaches)
                'coach_id' => $k % 2 === 0 ? $testUsersByRoleCode['c'][$k % 3] : null,
                'event_id' => ! empty($events) ? $events[0]->id : null
            ]);
            
            // All users in the set (including captain) are team members
            foreach ($participantUserSet as $participantUser) {
                $participantUser->team()->associate($team);

                $participantUser->save();
            }

            $teams[] = $team;
        }

        return $teams;
    }

    private function createTestEventsAndCases(): array
    {
        $events = [];

        $qtyTestEvents = 5;

        $maxQtyCasesPerEvent = 4;

        $this->command->info("Creating $qtyTestEvents test Events and up to $maxQtyCasesPerEvent Cases per Event");

        foreach (range(1, $qtyTestEvents) as $i) {
            $event = Event::factory()->create();

            // Make sure the first event created gets a full set of cases, the others get randomised amounts of cases
            $qtyCasesForThisEvent = $i === 1 ? $maxQtyCasesPerEvent : rand(0, $maxQtyCasesPerEvent);
            
            foreach (range(1, $qtyCasesForThisEvent) as $_i) {
                CaseModel::factory()->create([
                    'event_id' => $event->id
                ]);
            }

            $events[] = $event;
        }

        return $events;
    }

    private function createTestSubmissions(array $teams): void
    {
        $submissionCategories = SubmissionCategory::all();

        $maxQtyTestSubmissions = 16;

        $this->command->info("Creating up to $maxQtyTestSubmissions test Submissions per Team");

        $now = Carbon::now();

        foreach ($teams as $team) {
            $qtySubmissionsForThisTeam = rand(0, $maxQtyTestSubmissions);

            foreach (range(1, $qtySubmissionsForThisTeam) as $_i) {
                Submission::factory()->create([
                    'team_id' => $team->id,
                    'owner_id' => $team->members->random(),
                    'submission_category_id' => $submissionCategories->random()->id,
                    'case_id' => $team->event->cases->random()->id,
                    'created_at' => Carbon::now()->addMinutes(rand($_i - 3, $_i + 2))
                ]);
            }
        }
    }

    private function createTestTicketPurchases(array $events = [], array $participants = []): void
    {
        if (empty($events) || empty($participants)) {
            return;
        }

        $event_id = $events[0]->id;

        foreach (Arr::random($participants, floor(count($participants) / 2)) as $participant) {
            TicketPurchase::factory()->create([
                'event_id' => $event_id,
                'created_at' => fake()->dateTimeBetween('-20 week', 'now') 
            ]);
        }
    }
}
