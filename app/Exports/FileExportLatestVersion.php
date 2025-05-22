<?php

namespace App\Exports;

use App\Models\File;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FileExportLatestVersion implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return File::with(['status', 'record'])
            ->get()
            ->filter(fn ($file) => $file->isLatestVersion());
    }

    public function map($file): array
    {
        return [
            $file->title,
            $file->version,
            optional($file->status)->title,
            $file->sha256_hash ? 'Sí' : 'No',
            optional($file->record)->classification_code,
            $file->isLatestVersion() ? 'Sí' : 'No',
            $file->isCompliant() ? 'Sí' : 'No',
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
