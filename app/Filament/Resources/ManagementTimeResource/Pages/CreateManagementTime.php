<?php

namespace App\Filament\Resources\ManagementTimeResource\Pages;

use App\Filament\Resources\ManagementTimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateManagementTime extends CreateRecord
{
    protected static string $resource = ManagementTimeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
