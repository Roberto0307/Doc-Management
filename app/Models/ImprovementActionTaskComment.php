<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementActionTaskComment extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionTaskCommentFactory> */
    use HasFactory;

    protected $fillable = [
        'improvement_action_task_id',
        'comment',
    ];

    public function improvementActionTask()
    {
        return $this->belongsTo(ImprovementActionTask::class, 'improvement_action_task_id');
    }
}
