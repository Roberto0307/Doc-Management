<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Models\File;
use App\Services\FileService;
use Filament\Resources\Pages\Page;

class RestoreFile extends Page
{
    protected static string $resource = FileResource::class;

    protected static string $view = 'filament::pages.actions';

    public function mount(): void
    {

        abort_unless(File::find(request()->route('file')), 404);

        $file = request()->route('file');

        app(FileService::class)->restore($file);

        redirect()->to(FileResource::getUrl('index', [
            'record' => $file->record_id,
        ]));
    }
}
