<?php

namespace Database\Seeders;

use App\Models\Process;
use Illuminate\Database\Seeder;

class ProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /* Process::factory(3)->create(); */

        Process::factory()->create([
            'title' => 'Cadena de suministros',
        ]);
        Process::factory()->create([
            'title' => 'Calidad',
        ]);
        Process::factory()->create([
            'title' => 'Gestión financiera',
        ]);
        Process::factory()->create([
            'title' => 'Gestión humana',
        ]);
        Process::factory()->create([
            'title' => 'Investigación y desarrollo',
        ]);
    }
}
