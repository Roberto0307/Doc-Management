<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Resources\RecordResource;
use App\Models\Record;
use App\Services\AuthService;
use Filament\Resources\Pages\CreateRecord;

class CreateFile extends CreateRecord
{
    protected static string $resource = FileResource::class;

    public ?string $record_id = null;

    public function mount(): void
    {
        parent::mount();

        abort_unless(Record::find(request()->route('record')), 404);

        $this->record_id = request()->route('record')->id;

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['record_id'] = $this->record_id;

        return app(AuthService::class)->validatedData($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['record' => $this->record_id]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getSubheading(): ?string
    {
        return Record::find($this->record_id)?->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            RecordResource::getUrl('index') => 'Records',
            FileResource::getUrl('index', ['record' => $this->record_id]) => 'Files',
            false => 'Create',
        ];
    }
}
