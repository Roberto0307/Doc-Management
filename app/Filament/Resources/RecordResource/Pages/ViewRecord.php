<?php

namespace App\Filament\Resources\RecordResource\Pages;

use App\Filament\Resources\RecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord as BaseViewRecords;
use Filament\Actions\Action;
use App\Filament\Resources\FileResource;

class ViewRecord extends BaseViewRecords
{
    protected static string $resource = RecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Files')
                ->label('Versiones')
                ->button()
                ->color('info')
                ->url(fn (): string => FileResource::getUrl('index', ['record_id' => $this->data['id'] ])),

            Action::make('addFile')
                ->label('Upload file')
                ->button()
                ->url(fn (): string => FileResource::getUrl('create', ['record_id' => $this->data['id'] ])),
        ];
    }


}










