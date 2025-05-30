<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Resources\RecordResource;
use App\Models\Record;
use App\Services\AuthService;
use App\Services\FileService;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateFile extends CreateRecord
{
    protected static string $resource = FileResource::class;

    public $recordModel = null;

    public ?string $recordId = null;

    public function mount(): void
    {
        parent::mount();

        $this->recordId = request()->route('recordId');

        // Asegúrate de obtener el modelo real desde el ID
        $record = Record::findOrFail($this->recordId);

        // Guarda el modelo completo si lo vas a usar para el título o breadcrumbs
        $this->recordModel = $record;

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['record_id'] = $this->recordId;

        $data['sha256_hash'] = app(FileService::class)->generateDigitalSignature($data['file_path']);

        return app(AuthService::class)->validatedData($data);
    }

    protected function getRedirectUrl(): string
    {
        return RecordResource::getUrl('files.list', ['recordId' => $this->recordId]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('context')
                ->label($this->recordModel?->getContextPath())
                ->icon('heroicon-o-information-circle')
                ->disabled()
                ->color('gray'),
        ];
    }

    public function getSubheading(): ?string
    {
        return $this->recordModel?->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            RecordResource::getUrl('index') => 'Records',
            RecordResource::getUrl('files.list', ['recordId' => $this->recordId]) => 'Files',
            false => 'Create',
        ];
    }
}
