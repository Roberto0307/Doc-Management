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
                'label' => 'Draft',
                'color' => 'warning',
                'icon' => 'heroicon-o-pencil-square',
                'protected' => true,
            ],
            [
                'title' => 'pending',
                'label' => 'Pending',
                'color' => 'indigo',
                'icon' => 'heroicon-o-clock',
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
                'title' => 'rejected',
                'label' => 'Rejected',
                'color' => 'danger',
                'icon' => 'heroicon-o-x-circle',
                'protected' => true,
            ],
            [
                'title' => 'restore',
                'label' => 'Restore',
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
