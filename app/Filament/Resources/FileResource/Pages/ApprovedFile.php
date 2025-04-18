<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Models\File;
use App\Services\FileService;
use Filament\Resources\Pages\Page;

class ApprovedFile extends Page
{
    protected static string $resource = FileResource::class;

    protected static string $view = 'filament::pages.actions';

    public function mount(): void
    {
        $recordId = request()->route('record');

        app(FileService::class)->approved($recordId);

        $file = File::findOrFail($recordId);

        redirect()->to(
            FileResource::getUrl('index').'?'.http_build_query([
                'record_id' => $file->record_id,
            ])
        );

    }
}
