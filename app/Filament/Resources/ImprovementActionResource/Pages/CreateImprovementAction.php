<?php

namespace App\Filament\Resources\ImprovementActionResource\Pages;

use App\Filament\Resources\ImprovementActionResource;
use App\Services\ImprovementActionService;
use Filament\Resources\Pages\CreateRecord;

class CreateImprovementAction extends CreateRecord
{
    protected static string $resource = ImprovementActionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registration_date'] = now()->toDateString();
        $data['registered_by_id'] = auth()->id();
        $data['improvement_action_status_id'] = app(ImprovementActionService::class)->initialStateAssignment();

        /* dd($data); */

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
