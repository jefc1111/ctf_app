<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\TestDataSeeder;
use Database\Seeders\PermissionsAndRolesSeeder;
use Database\Seeders\SubmissionCategorySeeder;

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
            SubmissionCategorySeeder::class,
        ]);

        $this->call([
            TestDataSeeder::class,
        ]);
    }
}
