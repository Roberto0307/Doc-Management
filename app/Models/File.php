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
        'responses',
        'record_id',
        'user_id',
    ];

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
}
