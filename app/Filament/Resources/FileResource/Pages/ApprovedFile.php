<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Resources\RecordResource;
use App\Models\File;
use App\Services\FileService;
use Filament\Resources\Pages\Page;

class ApprovedFile extends Page
{
    protected static string $resource = FileResource::class;

    protected static string $view = 'filament::pages.actions';

    public function mount(): void
    {
        $file = File::findOrFail(request()->route('file'));

        app(FileService::class)->approved($file);

        redirect()->to(RecordResource::getUrl('files.list', [
            'recordId' => $file->record_id,
        ]));
    }
}
