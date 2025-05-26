<?php

namespace App\Filament\Resources\RecordResource\Widgets;

use App\Models\Record;
use Filament\Widgets\ChartWidget;

class RecordStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Record Status Chart';

    protected function getData(): array
    {
        $statusColors = [
            'approved' => 'rgba(22, 163, 74, 1)', // verde
            'rejected' => 'rgba(220, 38, 38, 1)', // rojo
            'pending' => 'rgba(79, 70, 229, 1)', // azul
            'draft' => 'rgba(251, 191, 36, 1)', // amarillo
            'Sin estado' => 'rgba(203, 213, 225, 1)', // gris para registros sin estado
        ];

        // Obtener todos los registros con su último archivo y estado
        $records = Record::with('latestFile.status')->get();

        // Agrupar por el campo "title" del status
        $grouped = $records->groupBy(function ($record) {
            return $record->latestFile?->status?->title ?? 'Sin estado';
        });

        // Contar registros por grupo
        $counts = $grouped->map(fn ($group) => $group->count());

        // Obtener los labels visibles para el gráfico (status->label o default capitalizado)
        $labels = $grouped->map(function ($group, $title) {
            return $group->first()->latestFile?->status?->label ?? ucfirst($title);
        });

        // Obtener colores desde el array manual
        $colors = $grouped->keys()->map(function ($title) use ($statusColors) {
            return $statusColors[$title] ?? '#999999';
        });

        return [
            'datasets' => [
                [
                    'label' => 'Document Statuses',
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
