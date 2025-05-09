<?php

namespace App\Filament\Resources\FinalDispositionResource\Pages;

use App\Filament\Resources\FinalDispositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinalDisposition extends EditRecord
{
    protected static string $resource = FinalDispositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
