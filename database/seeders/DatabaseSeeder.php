<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersAndTeamsSeeder;
use Database\Seeders\PermissionsAndRolesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsAndRolesSeeder::class,
        ]);

        $this->call([
            UsersAndTeamsSeeder::class,
        ]);






        // User::factory(10)->withPersonalTeam()->create();

        // User::factory()->withPersonalTeam()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
