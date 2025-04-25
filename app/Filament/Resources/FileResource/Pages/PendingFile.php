<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Models\File;
use App\Services\FileService;
use Filament\Resources\Pages\Page;

class PendingFile extends Page
{
    protected static string $resource = FileResource::class;

    protected static string $view = 'filament::pages.actions';

    public function mount(): void
    {

        $file = File::findOrFail(request()->route('file'));

        app(FileService::class)->pending($file);

        redirect()->to(FileResource::getUrl('index', [
            'recordId' => $file->record_id,
        ]));
    }
}
