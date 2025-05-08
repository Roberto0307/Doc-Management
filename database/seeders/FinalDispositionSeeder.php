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
            'title' => 'keep',
            'label' => 'Keep',
        ]);
        FinalDisposition::factory()->create([
            'title' => 'eliminate',
            'label' => 'Eliminate',
        ]);
        FinalDisposition::factory()->create([
            'title' => 'digitize',
            'label' => 'Digitize',
        ]);
    }
}
