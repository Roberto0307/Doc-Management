<?php

namespace App\Filament\Resources\ImprovementActionTaskResource\Pages;

use App\Filament\Resources\ImprovementActionTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImprovementActionTask extends EditRecord
{
    protected static string $resource = ImprovementActionTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
