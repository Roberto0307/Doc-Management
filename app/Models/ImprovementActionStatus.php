<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementActionStatus extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'label',
        'color',
        'icon',
        'protected',
    ];

    protected $casts = [
        'protected' => 'boolean',
    ];
}
