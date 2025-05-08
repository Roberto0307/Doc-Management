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
            'year' => 1,
        ]);
        ManagementTime::factory()->create([
            'year' => 2,
        ]);
        ManagementTime::factory()->create([
            'year' => 5,
        ]);
        ManagementTime::factory()->create([
            'year' => 10,
        ]);
    }
}
