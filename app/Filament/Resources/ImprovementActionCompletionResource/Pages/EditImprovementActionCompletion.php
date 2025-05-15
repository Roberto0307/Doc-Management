<?php

namespace App\Filament\Resources\ImprovementActionCompletionResource\Pages;

use App\Filament\Resources\ImprovementActionCompletionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImprovementActionCompletion extends EditRecord
{
    protected static string $resource = ImprovementActionCompletionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
