<?php

namespace App\Filament\Widgets;

use App\Models\Record;
use App\Services\RecordService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ComplianceStatusWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $totalRecords = Record::count();
        $recordsWithDisposition = Record::whereNotNull('final_disposition_id')->count();

        $recordsExpired = Record::with('centralTime')
            ->get()
            ->filter(fn ($record) => app(RecordService::class)->isExpired($record))
            ->count();

        return [
            Card::make('Total Records', $totalRecords),
            Card::make('With final disposition', $recordsWithDisposition)
                ->description($recordsWithDisposition >= $totalRecords ? '✔️ Complete' : '❗Incomplete'),
            Card::make('Expired registrations', $recordsExpired)
                ->description('These Registrations have expired'),
        ];
    }
}
