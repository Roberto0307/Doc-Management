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
        'title',
        'display_name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Métodos útiles
    |--------------------------------------------------------------------------
    */

    public static function byTitle(string $title): ?self
    {
        static $cache;

        if (! $cache) {
            $cache = self::all()->keyBy('title');
        }

        return $cache->get($title);
    }

    public static function DisplayNameFromId(string $id): ?string
    {
        return self::query()
            ->where('id', $id)
            ->value('display_name');
    }

    public static function titleFromDisplayName(string $statusDisplayName): ?string
    {
        return self::query()
            ->where('display_name', $statusDisplayName)
            ->value('title');
    }

    public static function colorFromTitle(string $title): ?string
    {

        $color = match ($title) {
            'Pending' => 'info',
            'Approved' => 'success',
            'Rejected' => 'danger',
            default => 'success',
        };

        return $color;
    }

    public static function displayNameFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('display_name');
    }

    // En espera de uso...

    public static function idFromTitle(string $title): ?int
    {
        return self::query()
            ->where('title', $title)
            ->value('id');
    }
}
