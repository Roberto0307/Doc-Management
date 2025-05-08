<?php

namespace App\Traits;

trait HasYearLabelOptions
{
    public static function selectOptions(): array
    {
        return static::all()
            ->mapWithKeys(fn ($item) => [$item->id => $item->year_label])
            ->toArray();
    }
}
