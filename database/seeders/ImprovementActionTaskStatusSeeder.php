<?php

namespace Database\Seeders;

use App\Models\ImprovementActionTaskStatus;
use Illuminate\Database\Seeder;

class ImprovementActionTaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $statuses = [
            [
                'title' => 'pending',
                'label' => 'Pendiente',
                'color' => 'gray',
                'icon' => 'heroicon-o-pencil-square',
                'protected' => true,
            ],
            /* [
                'title' => 'approved',
                'label' => 'Approved',
                'color' => 'indigo',
                'icon' => 'heroicon-o-hand-thumb-up',
                'protected' => true,
            ], */
            [
                'title' => 'in_execution',
                'label' => 'En ejecución',
                'color' => 'indigo',
                'icon' => 'heroicon-o-clock',
                'protected' => true,
            ],
            [
                'title' => 'completed',
                'label' => 'Completado',
                'color' => 'success',
                'icon' => 'heroicon-o-check-circle',
                'protected' => true,
            ],
            [
                'title' => 'overdue',
                'label' => 'Vencido',
                'color' => 'danger',
                'icon' => 'heroicon-o-x-circle',
                'protected' => true,
            ],
            [
                'title' => 'extemporaneous',
                'label' => 'Extemporaneo',
                'color' => 'warning',
                'icon' => 'heroicon-o-exclamation-triangle',
                'protected' => true,
            ],
        ];

        foreach ($statuses as $status) {
            ImprovementActionTaskStatus::updateOrCreate(
                ['title' => $status['title']],
                $status
            );
        }
    }
}
