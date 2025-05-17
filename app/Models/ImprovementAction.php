<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementAction extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'process_id',
        'sub_process_id',
        'improvement_action_origin_id',
        'registration_date',
        'registered_by_id',
        'responsible_id',
        'improvement_action_status_id',
        'expected_impact',
        'deadline',
        'actual_closing_date',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    }

    public function improvementActionOrigin()
    {
        return $this->belongsTo(ImprovementActionOrigin::class, 'improvement_action_origin_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function improvementActionStatus()
    {
        return $this->belongsTo(ImprovementActionStatus::class, 'improvement_action_status_id');
    }

    public function improvementActionTasks()
    {
        return $this->hasMany(ImprovementActionTask::class);
    }

    public function improvementActionCompletion()
    {
        return $this->hasOne(ImprovementActionCompletion::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    /* public function canFinishAction(): bool
    {
        return auth()->id() === $this->responsible_id;
    } */
}
