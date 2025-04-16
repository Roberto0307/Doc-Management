<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    /** @use HasFactory<\Database\Factories\RecordFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'process_id',
        'sub_process_id',
        'type_id',
        'user_id',
    ];

    // Relación con Tipo de Recordo
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    // Relación con Proceso
    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }
    // Relación con Sub proceso
    public function subprocess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    }
    // Relación con Usuarios
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
    public function latestApprovedFile() {
        return $this->hasOne(File::class)->where('status_id',2)->latest('version');
    }
}
