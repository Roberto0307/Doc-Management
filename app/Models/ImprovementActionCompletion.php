<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementActionCompletion extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionCompletionFactory> */
    use HasFactory;

    protected $fillable = [
        'improvement_action_id',
        'real_impact',
        'result',
    ];

    public function improvementAction()
    {
        return $this->belongsTo(ImprovementAction::class, 'improvement_action_id');
    }

    public function improvementActionCompletionFiles()
    {
        return $this->hasMany(ImprovementActionCompletionFile::class);
    }
}
