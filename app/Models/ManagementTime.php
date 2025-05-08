<?php

namespace App\Models;

use App\Traits\HasYearLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementTime extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory, HasYearLabel;

    protected $fillable = [
        'year',
    ];
}
