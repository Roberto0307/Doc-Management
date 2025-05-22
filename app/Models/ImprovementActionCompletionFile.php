<?php

namespace App\Models;

use App\Traits\HasDownloadableFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementActionCompletionFile extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionCompletionFileFactory> */
    use HasDownloadableFile, HasFactory;

    protected $fillable = [
        'improvement_action_completion_id',
        'file_name',
        'file_path',
    ];

    public function improvementActionCompletion()
    {
        return $this->belongsTo(ImprovementActionCompletion::class, 'improvement_action_completion_id');
    }
}
