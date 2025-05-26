<?php

namespace App\Models;

use App\Traits\HasDownloadableFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementActionTaskFile extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionTaskFileFactory> */
    use HasDownloadableFile, HasFactory;

    protected $fillable = [
        'improvement_action_task_id',
        'file_name',
        'file_path',
    ];

    public function improvementActionTask()
    {
        return $this->belongsTo(ImprovementActionTask::class, 'improvement_action_task_id');
    }
}
