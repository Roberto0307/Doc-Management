<?php

namespace App\Models;

use App\Traits\HasStatusMetadata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory, HasStatusMetadata;

    //
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
