<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;

    //
    protected $fillable = [
        'display_name'
    ];

    /*
    |--------------------------------------------------------------------------
    | Constantes de estado (opcional)
    |--------------------------------------------------------------------------
    */
    public const PENDING = 1;
    public const APPROVED = 2;
    public const REJECTED = 3;

    /*
    |--------------------------------------------------------------------------
    | Métodos útiles
    |--------------------------------------------------------------------------
    */

    public static function idFromTitle(string $title): ?int
    {
        return self::query()
            ->where('title', $title)
            ->value('id');
    }

    public static function displayNameFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('display_name');
    }
}
