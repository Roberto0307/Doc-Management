<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio de complementos
 */
class ComplementService
{
    public function getDownloadUrl(Model $record): string
    {
        return Storage::url($record->file_path);
    }
}
