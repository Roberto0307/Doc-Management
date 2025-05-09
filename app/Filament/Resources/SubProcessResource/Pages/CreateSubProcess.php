<?php

namespace App\Filament\Resources\SubProcessResource\Pages;

use App\Filament\Resources\SubProcessResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateSubProcess extends CreateRecord
{
    protected static string $resource = SubProcessResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = User::role('super_admin')->first()->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $superAdmin = User::role('super_admin')->first();

        if ($superAdmin && $this->record) {
            $this->record->users()->syncWithoutDetaching([$superAdmin->id]);
        }
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
