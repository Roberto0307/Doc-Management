<?php

namespace App\Filament\Resources\ManagementTimeResource\Pages;

use App\Filament\Resources\ManagementTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManagementTimes extends ListRecords
{
    protected static string $resource = ManagementTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
