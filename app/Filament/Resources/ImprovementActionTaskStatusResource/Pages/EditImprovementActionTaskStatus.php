<?php

namespace App\Filament\Resources\ImprovementActionTaskStatusResource\Pages;

use App\Filament\Resources\ImprovementActionTaskStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImprovementActionTaskStatus extends EditRecord
{
    protected static string $resource = ImprovementActionTaskStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
