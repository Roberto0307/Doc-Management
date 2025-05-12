<?php

namespace App\Filament\Resources\ImprovementActionStatusResource\Pages;

use App\Filament\Resources\ImprovementActionStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImprovementActionStatus extends EditRecord
{
    protected static string $resource = ImprovementActionStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
