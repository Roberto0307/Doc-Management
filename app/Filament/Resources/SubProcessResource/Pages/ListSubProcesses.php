<?php

namespace App\Filament\Resources\SubProcessResource\Pages;

use App\Filament\Resources\SubProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubProcesses extends ListRecords
{
    protected static string $resource = SubProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
