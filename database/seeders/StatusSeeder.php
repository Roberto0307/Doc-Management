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
                'icon' => 'heroicon-o-pencil-square',
                'protected' => true,
            ],
            [
                'title' => 'pending',
                'label' => 'Pendiente',
                'color' => 'indigo',
                'icon' => 'heroicon-o-clock',
                'protected' => true,
            ],
            [
                'title' => 'approved',
                'label' => 'Aprobado',
                'color' => 'success',
                'icon' => 'heroicon-o-check-circle',
                'protected' => true,
            ],
            [
                'title' => 'rejected',
                'label' => 'Rechazado',
                'color' => 'danger',
                'icon' => 'heroicon-o-x-circle',
                'protected' => true,
            ],
            [
                'title' => 'restore',
                'label' => 'Restaurado',
                'color' => 'warning',
                'icon' => 'heroicon-o-arrow-path',
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
