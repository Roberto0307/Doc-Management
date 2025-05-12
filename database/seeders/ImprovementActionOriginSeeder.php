<?php

namespace Database\Seeders;

use App\Models\ImprovementActionOrigin;
use Illuminate\Database\Seeder;

class ImprovementActionOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ImprovementActionOrigin::factory()->create([
            'title' => 'Sugerencia',
        ]);
        ImprovementActionOrigin::factory()->create([
            'title' => 'Auditoría',
        ]);
        ImprovementActionOrigin::factory()->create([
            'title' => 'Indicadores',
        ]);
    }
}
