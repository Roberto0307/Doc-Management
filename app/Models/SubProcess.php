<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProcess extends Model
{
    /** @use HasFactory<\Database\Factories\SubProcessFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'process_id',
        'acronym',
        'user_id',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_sub_process');
    }
}
