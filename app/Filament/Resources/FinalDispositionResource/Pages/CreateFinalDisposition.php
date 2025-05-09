<?php

namespace App\Filament\Resources\FinalDispositionResource\Pages;

use App\Filament\Resources\FinalDispositionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinalDisposition extends CreateRecord
{
    protected static string $resource = FinalDispositionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
