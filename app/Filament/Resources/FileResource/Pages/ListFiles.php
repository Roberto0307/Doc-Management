<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Resources\RecordResource;
use App\Models\Record;
use App\Models\Status;
use App\Services\AuthService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListFiles extends ListRecords
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

        // Verificación de permisos
        abort_if(! app(AuthService::class)->canAccessSubProcessId($record->sub_process_id), 403);

        if (session()->has('file_status')) {
            $data = session('file_status');
            $status = Status::byTitle($data['status_title']);

            Notification::make()
                ->title('Version successfully '.$status->label)
                ->icon($status->iconName())
                ->color($status->colorName())
                ->status($status->colorName())
                ->send();
        }
    }

    public function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getTableQuery();

        if ($this->recordId) {
            $query->where('record_id', $this->recordId)->orderByDesc('version');
        }

        return $query;
    }

    protected function getHeaderActions(): array
    {
        if (! $this->recordId) {
            return [];
        }

        return [
            Action::make('addFile')
                ->label('Upload file')
                ->button()
                ->authorize(fn ($record) => auth()->user()->can('create_file', $record))
                ->url(fn (): string => FileResource::getUrl('create', [
                    'recordId' => $this->recordId,
                ]
                )),
            Action::make('back')
                ->label('Return')
                ->url(fn (): string => RecordResource::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }

    public function getSubheading(): ?string
    {
        return $this->recordModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            RecordResource::getUrl('index') => 'Records',
            FileResource::getUrl('index', ['recordId' => $this->recordId]) => 'Files',
            false => 'List',
        ];
    }
}
