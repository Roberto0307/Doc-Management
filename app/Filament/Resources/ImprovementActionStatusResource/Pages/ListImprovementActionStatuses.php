<?php

namespace App\Filament\Resources\ImprovementActionStatusResource\Pages;

use App\Filament\Resources\ImprovementActionStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImprovementActionStatuses extends ListRecords
{
    protected static string $resource = ImprovementActionStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
