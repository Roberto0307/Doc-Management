<?php

namespace App\Filament\Resources\SubProcessResource\Pages;

use App\Filament\Resources\SubProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubProcess extends EditRecord
{
    protected static string $resource = SubProcessResource::class;

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
