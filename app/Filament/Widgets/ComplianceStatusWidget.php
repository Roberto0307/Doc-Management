<?php

namespace App\Filament\Widgets;

use App\Models\File;
use App\Models\Record;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;


class ComplianceStatusWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $totalRecords = Record::count();
        $recordsWithDisposition = Record::whereNotNull('final_disposition_id')->count();

        $recordsExpired = Record::with('centralTime')
            ->get()
            ->filter(function ($record) {
                if (!$record->centralTime?->year) return false;
                return $record->created_at->addYears($record->centralTime->year)->isPast();
            })->count();

        return [
            Card::make('Total Records', $totalRecords),
            Card::make('With final disposition', $recordsWithDisposition)
                ->description($recordsWithDisposition >= $totalRecords ? '✔️ Complete' : '❗Incomplete'),
            Card::make('Expired registrations', $recordsExpired)
                ->description('These Registrations have expired'),
        ];
    }
}

