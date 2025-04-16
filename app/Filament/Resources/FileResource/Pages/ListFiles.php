<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Resources\RecordResource;
use App\Models\Record;
use App\Services\FileService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;

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


        $statusURL = collect(['restored', 'approved', 'rejected'])
            ->first(fn ($key) => request()->has($key));

        if ($statusURL) {
            $getStatus = Str::ucfirst($statusURL);

            $status = FileService::getStatusByTitle($getStatus)?->display_name;

            if ($status) {
                Notification::make()
                    ->title("Version successfully {$status}")
                    ->success()
                    ->send();
            }
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
                ->url(fn (): string => FileResource::getUrl('create', [
                    'record_id' => $this->recordId,
                ])),
            Action::make('back')
                ->label('Return')
                ->url(fn (): string => RecordResource::getUrl('index'))
                ->button()
                ->color('info'),
        ];
    }

    // public function getSubheading(): ?string
    // {
    //     if (!$this->recordId) {
    //         return null;
    //     }

    //     $record = Record::find($this->recordId);

    //     if (!$record || !$record->latestApprovedFile) {
    //         return null;
    //     }

    //     return "Latest approved version of the document is: " . $record->latestApprovedFile->title;
    // }
}
