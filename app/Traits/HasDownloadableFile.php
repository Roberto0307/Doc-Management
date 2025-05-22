<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasDownloadableFile
{
    public function getDownloadUrl(): string
    {
        return Storage::url($this->file_path);
    }
}
