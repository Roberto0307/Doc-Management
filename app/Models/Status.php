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
        'label',
        'color',
        'icon',

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

    public static function labelFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('label');
    }

    public static function labelFromId(string $id): ?string
    {
        return self::query()
            ->where('id', $id)
            ->value('label');
    }

    public function isProtected(): bool
    {
        return $this->protected;
    }

    public function badgeColor(): string
    {
        return $this->color ?? 'gray';
    }

    public function iconName(): string
    {
        return $this->icon ?? 'information-circle';
    }
}
