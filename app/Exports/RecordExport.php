<?php

namespace App\Exports;

use App\Models\Record;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RecordExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $recordIds;

    public function __construct(array $recordIds)
    {
        $this->recordIds = $recordIds;
    }

    public function collection(): Collection
    {
        return Record::with([
            'type',
            'process',
            'subprocess',
            'user',
            'latestFile',
            'managementtime',
            'centraltime',
            'finaldisposition',
        ])
            ->whereIn('id', $this->recordIds)
            ->get();
    }

    public function map($record): array
    {
        return [
            $record->classification_code,
            $record->title,
            $record->type?->title,
            $record->process?->title,
            $record->subprocess?->title,
            $record->latestFile?->status?->label ?? __('Stateless'),
            $record->latestFile?->version ?? __('No version'),
            optional($record->user)->name,
            optional($record->managementtime)->year_label,
            optional($record->centraltime)->year_label,
            optional($record->finaldisposition)->label,
            $record->created_at?->format('Y-m-d H:i'),
            $record->updated_at?->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'Classification code',
            'Title',
            'Type',
            'Process',
            'Subprocess',
            'Status',
            'Version',
            'Created by',
            'Management time',
            'Central time',
            'Final disposition',
            'Created at',
            'Updated at',
        ];
    }
}
