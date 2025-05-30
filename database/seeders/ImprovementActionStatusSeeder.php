<?php

namespace Database\Seeders;

use App\Models\ImprovementActionStatus;
use Illuminate\Database\Seeder;

class ImprovementActionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $statuses = [
            [
                'title' => 'proposal',
                'label' => 'Propuesto',
                'color' => 'gray',
                'icon' => 'heroicon-o-pencil-square',
                'protected' => true,
            ],
            [
                'title' => 'in_execution',
                'label' => 'En ejecución',
                'color' => 'indigo',
                'icon' => 'heroicon-o-clock',
                'protected' => true,
            ],
            [
                'title' => 'finished',
                'label' => 'Finalizado',
                'color' => 'success',
                'icon' => 'heroicon-o-check-circle',
                'protected' => true,
            ],
            [
                'title' => 'canceled',
                'label' => 'Cancelado',
                'color' => 'danger',
                'icon' => 'heroicon-o-x-circle',
                'protected' => true,
            ],
        ];

        foreach ($statuses as $status) {
            ImprovementActionStatus::updateOrCreate(
                ['title' => $status['title']],
                $status
            );
        }
    }
}
