<?php

namespace App\Filament\Resources\ImprovementActionTaskStatusResource\Pages;

use App\Filament\Resources\ImprovementActionTaskStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImprovementActionTaskStatuses extends ListRecords
{
    protected static string $resource = ImprovementActionTaskStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
