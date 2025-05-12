<?php

namespace App\Filament\Resources\ImprovementActionResource\Pages;

use App\Filament\Resources\ImprovementActionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImprovementAction extends EditRecord
{
    protected static string $resource = ImprovementActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
