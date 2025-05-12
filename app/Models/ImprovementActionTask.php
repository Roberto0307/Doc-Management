<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementActionTask extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionTaskFactory> */
    use HasFactory;

    protected $fillable = [
        'improvement_action_id',
        'title',
        'detail',
        'responsible_id',
        'start_date',
        'deadline',
        'actual_start_date',
        'actual_closing_date',
        'improvement_action_task_status_id',
    ];

    public function improvementAction()
    {
        return $this->belongsTo(ImprovementAction::class, 'improvement_action_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function improvementActionTaskStatus()
    {
        return $this->belongsTo(ImprovementActionTaskStatus::class, 'improvement_action_task_status_id');
    }

    public function improvementActionTaskFiles()
    {
        return $this->hasMany(ImprovementActionTaskFile::class);
    }

    public function improvementActionTaskComments()
    {
        return $this->hasMany(ImprovementActionTaskComment::class);
    }
}
