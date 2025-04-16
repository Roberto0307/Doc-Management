<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Resources\RecordResource;
use App\Models\Record;
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

        $this->recordId = request()->query('record_id');

        //Validación de Usuario y sus subprocesos
        $user = auth()->user();
        $sub_process = Record::findOrFail($this->recordId)->sub_process_id;

        $isSuperAdmin = $user->hasRole('super_admin');
        $isAuthorized = $user->validSubProcess($sub_process ?? null);

        abort_if(!($isSuperAdmin || $isAuthorized), 403);

        if (session()->has('file_status')) {

            $data = session('file_status');

            Notification::make()
                ->title("Version successfully " .  $data['status'])
                ->success()
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
        if (!$this->recordId) {
            return [];
        }

        return [
            Action::make('addFile')
                ->label('Upload file')
                ->button()
                ->authorize(fn ($record) => auth()->user()->can('create_file', $record))
                ->url(fn (): string =>
                    FileResource::getUrl('create', [
                    'record_id' => $this->recordId,
                    ]
            )),
            Action::make('back')
                ->label('Return')
                ->url(fn (): string => RecordResource::getUrl('index'))
                ->button()
                ->color('info'),
        ];
    }
}
