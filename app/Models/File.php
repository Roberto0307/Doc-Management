<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'change_reason',
        'sha256_hash',
        'record_id',
        'user_id',
        'decided_by_user_id',
        'decision_at',
    ];

    protected $casts = [
        'version' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'decision_at' => 'datetime',
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

    public function decidedBy()
    {
        return $this->belongsTo(User::class, 'decided_by_user_id');
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

    public function isCompliant(): bool
    {
        return $this->isLatestVersion()
            && ! empty($this->sha256_hash)
            && optional($this->status)->title === 'approved'
            && optional($this->record)->classification_code
            && optional($this->record)->final_disposition_id;
    }
}
