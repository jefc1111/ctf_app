<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubmissionCategory;

class SubmissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Friends',
                'points' => '10'
            ],
            [
                'name' => 'Employment',
                'points' => '15'
            ],
            [
                'name' => 'Family',
                'points' => '20'
            ],
            [
                'name' => 'Basic Subject Info',
                'points' => '50'
            ],
            [
                'name' => 'Advanced Subject Info',
                'points' => '100'
            ],
            [
                'name' => 'Day Last Seen',
                'points' => '300'
            ],
            [
                'name' => 'Advancing the Timeline',
                'points' => '700'
            ],
            [
                'name' => 'Location',
                'points' => '5000'
            ]
        ];

        foreach ($categories as $category) {
            SubmissionCategory::create([
                'name' => $category['name'],
                'points' => $category['points']
            ]);
        }    
    }   
}
