<?php

namespace App\Services;

use App\Models\Record;
use App\Models\SubProcess;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class RecordService
{
    public function generateCode($typeId, $subProcessId): string
    {
        return DB::transaction(function () use ($typeId, $subProcessId) {

            $type = Type::lockForUpdate()->findOrFail($typeId);
            $subProcess = SubProcess::lockForUpdate()->findOrFail($subProcessId);

            $count = Record::where('type_id', $typeId)
                ->where('sub_process_id', $subProcessId)
                ->lockForUpdate()
                ->count();

            $consecutive = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            return "{$type->acronym}-{$subProcess->acronym}-{$consecutive}";
        });
    }

    public function isExpired(Record $record): bool
    {
        if (! $record->centralTime?->year || ! $record->created_at) {
            return false;
        }

        return $record->created_at->addYears($record->centralTime->year)->isPast();
    }

    public function expirationYearsRemaining(Record $record): ?int
    {
        if (! $record->centralTime?->year || ! $record->created_at) {
            return null;
        }

        $vencimiento = $record->created_at->copy()->addYears($record->centralTime->year);
        $hoy = now();

        if ($vencimiento->isPast()) {
            return 0;
        }

        return $hoy->diffInYears($vencimiento, false);
    }
}
