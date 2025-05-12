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
                'label' => 'Proposal',
                'color' => 'gray',
                'icon' => 'heroicon-o-pencil-square',
                'protected' => true,
            ],
            [
                'title' => 'approved',
                'label' => 'Approved',
                'color' => 'success',
                'icon' => 'heroicon-o-check-circle',
                'protected' => true,
            ],
            [
                'title' => 'in execution',
                'label' => 'In Execution',
                'color' => 'indigo',
                'icon' => 'heroicon-o-clock',
                'protected' => true,
            ],
            [
                'title' => 'finished',
                'label' => 'Finished',
                'color' => 'danger',
                'icon' => 'heroicon-o-arrow-path',
                'protected' => true,
            ],
            [
                'title' => 'canceled',
                'label' => 'Canceled',
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
