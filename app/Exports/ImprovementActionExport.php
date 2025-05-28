<?php

namespace App\Exports;

use App\Models\ImprovementAction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ImprovementActionExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $recordIds;

    public function __construct(array $recordIds)
    {
        $this->recordIds = $recordIds;
    }

    public function collection(): Collection
    {
        return ImprovementAction::with([
            'process',
            'subProcess',
            'improvementActionOrigin',
            'registeredBy',
            'responsible',
            'improvementActionStatus',
            'improvementActionCompletion',
        ])
            ->whereIn('id', $this->recordIds)
            ->get();
    }

    public function map($record): array
    {
        return [
            $record->id,
            $record->title,
            $record->description,
            $record->process?->title,
            $record->subProcess?->title,
            $record->improvementActionOrigin?->title,
            $record->registration_date,
            $record->registeredBy?->name,
            $record->responsible?->name,
            $record->improvementActionStatus?->label,
            $record->expected_impact,
            $record->deadline,
            $record->actual_closing_date ?? __('Unclosed'),
            $record->improvementActionCompletion?->real_impact ?? __('Unclosed'),
            $record->improvementActionCompletion?->result ?? __('Unclosed'),
            $record->created_at?->format('Y-m-d H:i'),
            $record->updated_at?->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Title'),
            __('Description'),
            __('Process'),
            __('Subprocess'),
            __('Improvement action origin'),
            __('Registration date'),
            __('Registered by'),
            __('Responsible'),
            __('Improvement action status'),
            __('Expected impact'),
            __('Deadline'),
            __('Actual closing date'),
            __('Real impact'),
            __('Result'),
            __('Created at'),
            __('Updated at'),
        ];
    }
}
