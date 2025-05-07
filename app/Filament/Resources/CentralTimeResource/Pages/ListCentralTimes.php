<?php

namespace App\Filament\Resources\CentralTimeResource\Pages;

use App\Filament\Resources\CentralTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentralTimes extends ListRecords
{
    protected static string $resource = CentralTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
