<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'classification_code',
        'title',
        'process_id',
        'sub_process_id',
        'type_id',
        'user_id',
        'management_time_id',
        'central_time_id',
        'final_disposition_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function finalDisposition()
    {
        return $this->belongsTo(finalDisposition::class, 'final_disposition_id');
    }

    public function centralTime()
    {
        return $this->belongsTo(CentralTime::class, 'central_time_id');
    }

    public function managementTime()
    {
        return $this->belongsTo(ManagementTime::class, 'management_time_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function latestFile()
    {
        return $this->hasOne(File::class)->latestOfMany('version');
    }

    public function latestApprovedFile()
    {
        return $this->hasOne(File::class)
            ->where('status_id', 3)
            ->latest('version');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function approvedVersionUrl(): ?string
    {
        return $this->latestApprovedFile?->file_path
            ? Storage::url($this->latestApprovedFile->file_path)
            : null;
    }

    public function hasApprovedVersion(): bool
    {
        return ! empty($this->latestApprovedFile?->file_path);
    }

    public function canBeAccessedBy(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->validSubProcess($this->sub_process_id);
    }

    public function getContextPath(): string
    {
        $processTitle = $this->process?->title ?? null;
        $subprocessTitle = $this->subProcess?->title ?? null;

        return "{$processTitle} / {$subprocessTitle}";
    }
}
