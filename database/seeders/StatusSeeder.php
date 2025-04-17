<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Status::factory()->create([
            'title' => 'Pending',
            'display_name' => 'Pending',
        ]);

        Status::factory()->create([
            'title' => 'Approved',
            'display_name' => 'Approved',
        ]);

        Status::factory()->create([
            'title' => 'Rejected',
            'display_name' => 'Rejected',
        ]);

        Status::factory()->create([
            'title' => 'Restore',
            'display_name' => 'Restore',
        ]);
    }
}
