<?php

namespace Database\Seeders;

use App\Models\ManagementTime;
use Illuminate\Database\Seeder;

class ManagementTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ManagementTime::factory()->create([
            'title' => '1 año',
            'year' => 1,
        ]);
        ManagementTime::factory()->create([
            'title' => '2 años',
            'year' => 2,
        ]);
        ManagementTime::factory()->create([
            'title' => '5 años',
            'year' => 5,
        ]);
        ManagementTime::factory()->create([
            'title' => '10 años',
            'year' => 10,
        ]);
    }
}
