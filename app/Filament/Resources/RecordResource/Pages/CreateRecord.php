<?php

namespace App\Filament\Resources\RecordResource\Pages;

use App\Filament\Resources\RecordResource;
use App\Services\AuthService;
use App\Services\RecordService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord as BaseCreateRecord;

class CreateRecord extends BaseCreateRecord
{
    protected static string $resource = RecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        if (! app(AuthService::class)->canAccessSubProcessId($data['sub_process_id'] ?? null)) {
            Notification::make()
                ->title('Access denied')
                ->body('You do not have permission to create this file.')
                ->danger()
                ->persistent()
                ->send();
            $this->halt();
        }

        $data['code'] = app(RecordService::class)->generateCode($data['type_id'], $data['sub_process_id']);
        $data['user_id'] = $user->id;

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
