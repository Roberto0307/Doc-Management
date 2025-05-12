<?php

namespace App\Filament\Resources\ImprovementActionResource\Pages;

use App\Filament\Resources\ImprovementActionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImprovementActions extends ListRecords
{
    protected static string $resource = ImprovementActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
