<?php

namespace App\Services;

use App\Models\File;
use App\Models\Status;
use App\Notifications\FileStatusUpdated;
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
        $responses = 'Pending from version '.$file->version;

        $this->updateFileStatus($file, $status, $responses);
    }

    public function rejected(int $id): void
    {
        $file = File::findOrFail($id);
        $status = Status::byTitle('rejected');
        $responseMessage = 'Rejected from version '.$file->version;
        $responses = Str::limit(strip_tags(request()->query('responses', $responseMessage)), 255);

        $this->updateFileStatus($file, $status, $responses);
    }

    public function approved(int $id): void
    {
        $file = File::findOrFail($id);

        $data = [
            'record_id' => $file->record_id,
            'responses' => 'Approved from version '.$file->version,
            'user_id' => $file->user_id,
        ];

        $validated = $this->authService->validatedData($data, ['user_id']);

        DB::transaction(fn () => $file->update($validated));

        $status = Status::byTitle('approved');
        $this->notifyStatusChange($file, $status, $data['responses']);
    }

    public function restore(int $id): void
    {
        $file = File::findOrFail($id);

        $data = [
            'title' => $file->title,
            'record_id' => $file->record_id,
            'sub_process_id' => $file->record->sub_process_id,
            'file_path' => $file->file_path,
            'comments' => Str::limit(strip_tags(request()->query('comment', $file->comments)), 255),
            'responses' => 'Restored from version '.$file->version,
            'user_id' => $file->user_id,
        ];

        $validated = $this->authService->validatedData($data, ['user_id']);

        DB::transaction(fn () => File::create($validated));

        $status = Status::findOrFail($validated['status_id']);
        $this->notifyStatusChange($file, $status, $data['responses']);
    }

    private function updateFileStatus(File $file, Status $status, ?string $responses = null, array $extra = []): void
    {
        $file->update(array_merge([
            'status_id' => $status->id,
            'responses' => $responses,
        ], $extra));

        $this->notifyStatusChange($file, $status, $responses ?? '');
    }

    protected function notifyStatusChange(File $file, Status $status, string $message): void
    {
        $notifiables = collect([auth()->user(), $file->user])->filter()->unique('id');

        Notification::send($notifiables, new FileStatusUpdated($file, $status, $message));

        session()->flash('file_status', [
            'status_title' => $status->title,
        ]);
    }
}
