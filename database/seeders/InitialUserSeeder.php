<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class InitialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = env('INITIAL_ADMIN_PASSWORD');

        if (! $password) {
            abort(400, "No INITIAL_ADMIN_PASSWORD set in .env");
        }

        $user = User::factory()->withPersonalTeam()->create([
            'name'     => 'Admin',
            'email'    => 'geoff.c+admin@tracelabs.org',
            'password' => bcrypt($password),
        ]);
 
        $user->assignRole('Super Admin');
    }
}
