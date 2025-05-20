<?php

namespace App\Filament\Resources\ImprovementActionTaskResource\Pages;

use App\Filament\Resources\ImprovementActionTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImprovementActionTasks extends ListRecords
{
    protected static string $resource = ImprovementActionTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
