<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementActionTaskFile extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionTaskFileFactory> */
    use HasFactory;

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
