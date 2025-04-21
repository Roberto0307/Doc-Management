<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'status_id',
        'version',
        'comments',
        'responses',
        'record_id',
        'user_id',
    ];

    protected $casts = [
        'version' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function isLatestVersion(): bool
    {
        return $this->id === self::where('record_id', $this->record_id)
            ->orderByDesc('version')
            ->first()?->id;
    }

    public function getDownloadUrl(): string
    {
        return Storage::url($this->file_path);
    }

    public function getDownloadButtonHtml(): string
    {
        $url = $this->getDownloadUrl();
        $filename = $this->title;

        return <<<HTML
    <a href="{$url}" download="{$filename}"
        style="--c-400:var(--success-400);--c-500:var(--success-500);--c-600:var(--success-600);"
        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75
               focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-success fi-color-success fi-size-md fi-btn-size-md
               gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm
               bg-custom-600 text-white hover:bg-custom-500
               focus-visible:ring-custom-500/50
               dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">

        <span class="fi-btn-label">Download</span>
    </a>
    HTML;
    }
}
