<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    /** @use HasFactory<\Database\Factories\ProcessFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function subprocesses()
    {
        return $this->hasMany(SubProcess::class);
    }
}
