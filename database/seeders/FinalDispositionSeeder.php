<?php

namespace Database\Seeders;

use App\Models\FinalDisposition;
use Illuminate\Database\Seeder;

class FinalDispositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        FinalDisposition::factory()->create([
            'title' => 'Conservar',
        ]);
        FinalDisposition::factory()->create([
            'title' => 'Eliminar',
        ]);
        FinalDisposition::factory()->create([
            'title' => 'Digitalizar',
        ]);
    }
}
