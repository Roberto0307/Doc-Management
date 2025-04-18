<?php

namespace App\Filament\Resources\SubProcessResource\Pages;

use App\Filament\Resources\SubProcessResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubProcess extends CreateRecord
{
    protected static string $resource = SubProcessResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
