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
        'digital_signature',
        'record_id',
        'user_id',
        'leader_id',
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

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
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
}
