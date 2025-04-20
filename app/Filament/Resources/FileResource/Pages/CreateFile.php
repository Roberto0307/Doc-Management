<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Models\Record;
use App\Models\User;
use App\Notifications\FileStatusUpdated;
use App\Services\AuthService;
use Filament\Resources\Pages\CreateRecord;

class CreateFile extends CreateRecord
{
    protected static string $resource = FileResource::class;

    public ?int $record_id = null;

    public function mount(): void
    {
        parent::mount();

        $this->record_id = request()->query('record_id');

        $this->form->fill([
            'record_id' => $this->record_id,
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return app(AuthService::class)->validatedData($data);
    }

    protected function afterCreate(): void
    {
        $file = $this->record;
        $status = $file->status;
        $comments = $file->comments;

        $user = User::role('super_admin')->first();
        $user->notify(new FileStatusUpdated($file, $status, $comments));
    }

    protected function getRedirectUrl(): string
    {
        $recordId = $this->record->record_id;

        return $this->getResource()::getUrl('index', ['record_id' => $recordId]);

    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getSubheading(): ?string
    {
        if (! $this->record_id) {
            return null;
        }

        $record = Record::find($this->record_id);

        return $record?->title;
    }
}
