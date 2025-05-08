<?php

namespace App\Models;

use App\Traits\HasYearLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentralTime extends Model
{
    /** @use HasFactory<\Database\Factories\CentralTimeFactory> */
    use HasFactory, HasYearLabel;

    protected $fillable = [
        'year',
    ];
}
