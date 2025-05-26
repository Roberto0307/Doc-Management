<?php

namespace App\Exports;

use App\Models\File;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FileExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $fileIds;

    public function __construct(array $fileIds)
    {
        $this->fileIds = $fileIds;
    }

    public function collection(): Collection
    {
        return File::with(['status', 'record'])
            ->whereIn('id', $this->fileIds)
            ->get();
    }

    public function map($file): array
    {
        return [
            $file->title,
            $file->version,
            optional($file->status)->title,
            $file->sha256_hash ? 'Yes' : 'No',
            optional($file->record)->classification_code,
            $file->isLatestVersion() ? 'Yes' : 'No',
            $file->isCompliant() ? 'Yes' : 'No',
            $file->change_reason ?? '—',
            $file->created_at,
            $file->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'File',
            'Version',
            'Status',
            'Signed',
            'Classification',
            'Latest Version',
            'Meets Requirements',
            'Reason for change',
            'Created',
            'Updated',
        ];
    }
}
