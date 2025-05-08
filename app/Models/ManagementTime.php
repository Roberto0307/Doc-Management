<?php

namespace App\Models;

use App\Traits\HasYearLabelOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementTime extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory, HasYearLabelOptions;

    protected $fillable = [
        'year',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function getYearLabelAttribute(): string
    {
        return trans_choice(':n Year|:n Years', $this->year, ['n' => $this->year]);
    }
}
