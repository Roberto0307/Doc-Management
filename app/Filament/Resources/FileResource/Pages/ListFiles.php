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

    public ?int $recordId = null;

    public function mount(): void
    {

        parent::mount();

        abort_unless(Record::find(request()->query('record_id')), 404);

        $this->recordId = request()->query('record_id');

        $sub_processId = Record::findOrFail($this->recordId)->sub_process_id;

        abort_if(! app(AuthService::class)->canAccessSubProcessId($sub_processId), 403);

        if (session()->has('file_status')) {

            $data = session('file_status');

            // Obtener status usando el 'title'
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
                    'record_id' => $this->recordId,
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
        if (! $this->recordId) {
            return null;
        }

        $record = Record::find($this->recordId);

        return $record?->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            FileResource::getUrl('index', ['record_id' => $this->recordId]) => 'Files',
            false => 'List',
        ];
    }
}
