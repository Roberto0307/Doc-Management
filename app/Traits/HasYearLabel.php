<?php

namespace App\Traits;

trait HasYearLabel
{
    public function getYearLabelAttribute(): string
    {
        return trans_choice(':n Year|:n Years', $this->year, ['n' => $this->year]);
    }

    public static function selectOptions(): array
    {
        return static::orderBy('year')->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->year_label])
            ->toArray();
    }
}
