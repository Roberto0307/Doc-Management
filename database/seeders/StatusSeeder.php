<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'title' => 'Draft', 'display_name' => 'Borrador'],
            ['id' => 2, 'title' => 'Pending', 'display_name' => 'Pendiente'],
            ['id' => 3, 'title' => 'Approved', 'display_name' => 'Aprobado'],
            ['id' => 4, 'title' => 'Rejected', 'display_name' => 'Rechazado'],
            ['id' => 5, 'title' => 'Restore', 'display_name' => 'Restaurado'],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['id' => $status['id']],
                $status
            );
        }
    }
}
