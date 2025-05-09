<?php

namespace App\Filament\Resources\CentralTimeResource\Pages;

use App\Filament\Resources\CentralTimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCentralTime extends CreateRecord
{
    protected static string $resource = CentralTimeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
