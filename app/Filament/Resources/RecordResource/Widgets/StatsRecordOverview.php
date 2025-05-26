<?php

namespace App\Filament\Resources\RecordResource\Widgets;

use App\Models\Record;
use App\Services\RecordService;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsRecordOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRecords = Record::count();
        $recordsWithDisposition = Record::whereNotNull('final_disposition_id')->count();

        $recordsExpired = Record::with('centralTime')
            ->get()
            ->filter(fn($record) => app(RecordService::class)->isExpired($record))
            ->count();

        return [
            Stat::make('Total Records', $totalRecords)
                ->description('Registers in the system')
                ->descriptionIcon('heroicon-m-numbered-list', IconPosition::Before),
            Stat::make('With final disposition', $recordsWithDisposition)
                ->description($recordsWithDisposition >= $totalRecords ? 'Complete' : 'Incomplete')
                ->descriptionIcon($recordsWithDisposition >= $totalRecords ? 'heroicon-m-check' : 'heroicon-m-exclamation-circle', IconPosition::Before)
                ->color($recordsWithDisposition >= $totalRecords ? 'success' : 'danger'),
            Stat::make('Expired registrations', $recordsExpired)
                ->description('These Registrations have expired'),
        ];
    }
}
