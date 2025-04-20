<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'title' => 'draft',
                'label' => 'Borrador',
                'color' => 'gray',
                'icon' => 'pencil-square',
                'protected' => true,
            ],
            [
                'title' => 'pending',
                'label' => 'Pendiente',
                'color' => 'warning',
                'icon' => 'clock',
                'protected' => true,
            ],
            [
                'title' => 'approved',
                'label' => 'Aprobado',
                'color' => 'success',
                'icon' => 'check-circle',
                'protected' => true,
            ],
            [
                'title' => 'rejected',
                'label' => 'Rechazado',
                'color' => 'danger',
                'icon' => 'x-circle',
                'protected' => true,
            ],
            [
                'title' => 'restore',
                'label' => 'Restaurado',
                'color' => 'secondary',
                'icon' => 'arrow-path',
                'protected' => true,
            ],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['title' => $status['title']],
                $status
            );
        }
    }
}
