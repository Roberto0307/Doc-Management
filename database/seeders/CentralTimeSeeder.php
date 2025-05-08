<?php

namespace Database\Seeders;

use App\Models\CentralTime;
use Illuminate\Database\Seeder;

class CentralTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CentralTime::factory()->create([
            'year' => 2,
        ]);
        CentralTime::factory()->create([
            'year' => 5,
        ]);
        CentralTime::factory()->create([
            'year' => 10,
        ]);
        CentralTime::factory()->create([
            'year' => 15,
        ]);
    }
}
