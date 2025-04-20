<?php

namespace App\Services;

use App\Models\File;
use App\Models\Status;
use App\Notifications\FileStatusUpdated;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * Servicio de los Archivos
 */
class FileService
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function pending(int $id): void
    {
        $file = File::findOrFail($id);
        $status = Status::byTitle('pending');

        $responseMessage = 'Pending from version '.$file->version;
        $responses = Str::limit(strip_tags(request()->query('responses', $responseMessage)), 255);

        $file->update([
            'status_id' => $status->id,
            'responses' => $responses,
        ]);

        $this->notifyStatusChange($file, $status, $responses);
    }

    public function rejected(int $id): void
    {
        $file = File::findOrFail($id);
        $status = Status::byTitle('rejected');

        $responseMessage = 'Rejected from version '.$file->version;
        $responses = Str::limit(strip_tags(request()->query('responses', $responseMessage)), 255);

        $file->update([
            'status_id' => $status->id,
            'responses' => $responses,
        ]);

        $this->notifyStatusChange($file, $status, $responses);
    }

    public function approved(int $id): void
    {
        $file = File::findOrFail($id);

        $data = [
            'record_id' => $file->record_id,
            'responses' => 'Approved from version '.$file->version,
        ];

        $validated = $this->authService->validatedData($data);

        // Solo queremos actualizar estos campos
        $validated = Arr::only($validated, ['status_id', 'version', 'responses']);

        DB::transaction(function () use ($file, $validated) {
            $file->update($validated);
        });

        $status = Status::byTitle('approved');

        $this->notifyStatusChange($file, $status, $data['responses']);

    }

    public function restore(int $id): void
    {
        $file = File::findOrFail($id);

        $data = [
            'title' => $file->title,
            'record_id' => $file->record_id,
            'sub_process_id' => $file->sub_process_id,
            'file_path' => $file->file_path,
            'comments' => Str::limit(strip_tags(request()->query('comment', $file->comments)), 255),
            'responses' => 'Restored from version '.$file->version,
        ];

        $validated = $this->authService->validatedData($data);

        DB::transaction(function () use ($validated) {
            File::create($validated);
        });

        $status = Status::findOrFail($validated['status_id']);

        $this->notifyStatusChange($file, $status, $data['responses']);
    }

    protected function notifyStatusChange(File $file, Status $status, string $message): void
    {

        $notifiables = collect([
            auth()->user(),
            $file->user,
        ])
            ->filter()
            ->unique('id');

        Notification::send($notifiables, new FileStatusUpdated($file, $status, $message));

        session()->flash('file_status', [
            'status_title' => $status->title,
        ]);

    }
}
