<?php

namespace App\Filament\Resources\RecordResource\Pages;

use App\Filament\Resources\RecordResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord as BaseCreateRecord;
use App\Services\RecordService;
use Filament\Notifications\Notification;


class CreateRecord extends BaseCreateRecord
{
    protected static string $resource = RecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        $isSuperAdmin = $user->hasRole('super_admin');
        $isAuthorized = $user->validSubProcess($data['sub_process_id'] ?? null);

        if (!($isSuperAdmin || $isAuthorized)) {
            Notification::make()
                ->title('Acceso denegado')
                ->body('No tienes permiso para crear este archivo.')
                ->danger()
                ->persistent()
                ->send();

            $this->halt(); // Detiene el proceso de creación
        }

        $data['code'] = RecordService::generateCode($data['type_id'], $data['sub_process_id']);
        $data['user_id'] = auth()->id();

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


