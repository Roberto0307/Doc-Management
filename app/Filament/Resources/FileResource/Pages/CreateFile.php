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

    // protected function afterCreate(): void
    // {
    //     $file = $this->record;
    //     $status = $file->status;
    //     $comments = $file->comments;

    //     $authService = app(AuthService::class);

    //     // Obtener el usuario dueño del subproceso (si existe)
    //     $owner = $authService->getOwnerToSubProcess($file->record->sub_process_id);

    //     // Si no hay dueño, buscar un super_admin
    //     if (! $owner) {
    //         $owner = User::role('super_admin')->first(); // Usa Spatie
    //     }

    //     // Si encontramos alguien a quien notificar
    //     if ($owner) {

    //         $owner->notify(new FileStatusUpdated($file, $status, $comments));
    //     }

    // }

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

    public function getBreadcrumbs(): array
    {
        return [
            FileResource::getUrl('index', ['record_id' => $this->record_id]) => 'Files',
            false => 'Create',
        ];
    }
}
