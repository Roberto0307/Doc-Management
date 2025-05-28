<?php

namespace App\Filament\Resources\ImprovementActionResource\Widgets;

use App\Models\ImprovementAction;
use Filament\Widgets\ChartWidget;

class ImprovementActionStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Improvement Action Status Chart';

    protected function getData(): array
    {
        $statusColors = [
            'finished' => 'rgba(22, 163, 74, 1)', // verde
            'canceled' => 'rgba(220, 38, 38, 1)', // rojo
            'in_execution' => 'rgba(79, 70, 229, 1)', // azul
            'proposal' => 'rgba(161, 161, 170, 1)', // amarillo
            'Sin estado' => 'rgba(203, 213, 225, 1)', // gris para registros sin estado
        ];

        // Obtener todos los registros con su último archivo y estado
        $records = ImprovementAction::with('improvementActionStatus')->get();
        // dd($records);

        // Agrupar por el campo "title" del status
        $grouped = $records->groupBy(function ($record) {
            return $record->improvementActionStatus?->title ?? 'Sin estado';
        });

        // Contar registros por grupo
        $counts = $grouped->map(fn ($group) => $group->count());

        // Obtener los labels visibles para el gráfico (status->label o default capitalizado)
        $labels = $grouped->map(function ($group, $title) {
            return $group->first()->improvementActionStatus?->label ?? ucfirst($title);
        });

        // Obtener colores desde el array manual
        $colors = $grouped->keys()->map(function ($title) use ($statusColors) {
            return $statusColors[$title] ?? '#999999';
        });

        return [
            'datasets' => [
                [
                    'label' => 'Improvement Action Statuses',
                    'data' => $counts->values()->toArray(),
                    'backgroundColor' => $colors->values()->toArray(),
                ],
            ],
            'labels' => $labels->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
