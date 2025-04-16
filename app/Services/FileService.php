<?php
namespace App\Services;
use App\Models\File;
use App\Models\Status;
use App\Notifications\FileStatusUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * Servicio de los Archivos
 */

class FileService
{

    protected static ?\Illuminate\Support\Collection $cachedStatuses = null;


    public static function getStatusByTitle(string $title): ?Status
    {
        if (is_null(self::$cachedStatuses)) {
            self::$cachedStatuses = Status::all()->keyBy('title');
        }

        return self::$cachedStatuses->get($title);
    }


    public static function rejected(int $id): void
    {
        $file = File::findOrFail($id);
        $status = self::getStatusByTitle('Rejected');
        $responseMessage = 'Rejected from version ' . $file->version;
        $responses = Str::limit(strip_tags(request()->query('responses', $responseMessage)), 255);

        $file->update([
            'status_id' => $status->id,
            'responses' => $responses,
        ]);

        self::notifyStatusChange($file, $status->display_name, $responses);
    }

    public static function approved(int $id): void
    {
        $file = File::findOrFail($id);

        $data = [
            'record_id' => $file->record_id,
            'responses' => 'Approved from version ' . $file->version,
        ];

        $validated = self::validatedData($data);

        // Solo queremos actualizar estos campos
        $validated = Arr::only($validated, ['status_id', 'version','responses']);

        DB::transaction(function () use ($file, $validated) {
            $file->update($validated);
        });

        $status = self::getStatusByTitle('Approved');

        self::notifyStatusChange($file, $status->display_name, $data['responses']);
    }

    public static function restore(int $id): void
    {
        $file = File::findOrFail($id);

        $data = [
            'title' => $file->title,
            'record_id' => $file->record_id,
            'sub_process_id' => $file->sub_process_id,
            'file_path' => $file->file_path,
            'comments' => Str::limit(strip_tags(request()->query('comment', $file->comments)), 255),
            'responses' => 'Restored from version ' . $file->version,
        ];

        $validated = self::validatedData($data);

        DB::transaction(function () use ($validated) {
            File::create($validated);
        });

        $status = Status::where('id', $validated['status_id'])->first()->display_name;

        self::notifyStatusChange($file, $status, $data['responses']);
    }

    public static function validatedData($data)
    {
        $user = auth()->user();

        $statusApproved = self::getStatusByTitle('Approved');
        $statusPending  = self::getStatusByTitle('Pending');

        $lastVersion = File::where('record_id', $data['record_id'])->orderByDesc('version')->first();

        if ($lastVersion) {
            if ($user->hasRole('super_admin')) {

                $newVersion = (string) floor((float)$lastVersion->version + 1);

            } else {

                $newVersion = bcadd($lastVersion->version, '0.1', 1);

            }
        } else {

            $newVersion = $user->hasRole('super_admin') ? '1.0' : '0.1';
        }

        $status_id = $user->hasRole('super_admin') ? $statusApproved->id : $statusPending->id;

        $data['status_id'] = $status_id;
        $data['version'] =  $newVersion;
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected static function notifyStatusChange(File $file, string $statusTitle, string $message): void
    {

        $notifiables = collect([
            auth()->user(),
            $file->user,
        ])
        ->filter()
        ->unique('id');

        Notification::send($notifiables, new FileStatusUpdated($file, $statusTitle, $message));
    }


    public static function generateButton($state, $record)
    {
        $title = $record->title ?? 'Descargar';
        $url = Storage::url($state);

        $filename = $title . '.' . pathinfo($state, PATHINFO_EXTENSION);

        return "<a href='{$url}' download='{$filename}' class='inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-md hover:bg-primary-700 transition'>Descargar</a>";
    }

}






