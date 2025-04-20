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

    public static function displayNameFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('display_name');
    }

    public static function idFromDisplayName(string $DisplayName): ?int
    {
        return self::query()
            ->where('display_name', $DisplayName)
            ->value('id');
    }

    public static function displayNameFromId(string $id): ?string
    {
        return self::query()
            ->where('id', $id)
            ->value('display_name');
    }

    public static function colorFromId(?int $id): string
    {
        return match ($id) {
            1 => 'gray',     // Draft
            2 => 'info',     // Pending
            3 => 'success',  // Approved
            4 => 'danger',   // Rejected
            default => 'secondary',
        };
    }
}
