<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementActionTaskStatus extends Model
{
    /** @use HasFactory<\Database\Factories\ImprovementActionTaskStatusFactory> */
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

    public static function iconFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('icon');
    }

    public static function colorFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('color');
    }

    public function isProtected(): bool
    {
        return $this->protected;
    }

    public function colorName(): string
    {
        return $this->color ?? 'gray';
    }

    public function iconName(): string
    {
        return $this->icon ?? 'heroicon-o-information-circle';
    }
}
