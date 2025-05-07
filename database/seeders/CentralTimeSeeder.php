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
            'title' => '2 años',
            'year' => 2,
        ]);
        CentralTime::factory()->create([
            'title' => '5 años',
            'year' => 5,
        ]);
        CentralTime::factory()->create([
            'title' => '10 años',
            'year' => 10,
        ]);
        CentralTime::factory()->create([
            'title' => '15 años',
            'year' => 15,
        ]);
    }
}
