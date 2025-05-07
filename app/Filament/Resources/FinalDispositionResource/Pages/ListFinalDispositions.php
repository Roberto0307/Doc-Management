<?php

namespace App\Filament\Resources\FinalDispositionResource\Pages;

use App\Filament\Resources\FinalDispositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinalDispositions extends ListRecords
{
    protected static string $resource = FinalDispositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
