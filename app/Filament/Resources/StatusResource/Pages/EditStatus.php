<?php

namespace App\Filament\Resources\StatusResource\Pages;

use App\Filament\Resources\StatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStatus extends EditRecord
{
    protected static string $resource = StatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(function () {
                    return auth()->user()->hasRole('super_admin') ||
                    ! $this->record->isProtected();
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
