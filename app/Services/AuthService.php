<?php

namespace App\Services;

use App\Models\File;
use App\Models\Record;
use App\Models\Status;
use App\Models\User;

/**
 * Servicio de Autentificación
 */
class AuthService
{
    public function canApprove(User $user, ?int $subProcessId): bool
    {
        return $user->hasRole('super_admin') ||
               ($user->hasRole('pro') && $user->validSubProcess($subProcessId));
    }

    public function validatedData(array $data): array
    {
        $user = auth()->user();

        $record = Record::with('subProcess')->findOrFail($data['record_id']);

        $hasApprovalAccess = $this->canApprove(
            $user,
            $record->sub_process_id ?? null
        );

        $statusApproved = Status::byTitle('Approved');
        $statusPending = Status::byTitle('Pending');

        $lastVersion = File::where('record_id', $data['record_id'])
            ->orderByDesc('version')
            ->first();

        if ($lastVersion) {
            if ($hasApprovalAccess) {
                // Extrae la parte entera de la versión y suma 1
                $major = (int) $lastVersion->version;
                $newVersion = ($major + 1).'.0';

            } else {

                // Incrementa decimal en 0.1
                $newVersion = bcadd($lastVersion->version, '0.1', 1);
            }

        } else {

            $newVersion = $hasApprovalAccess ? '1.0' : '0.1';
        }

        $data['status_id'] = $hasApprovalAccess ? $statusApproved->id : $statusPending->id;
        $data['version'] = $newVersion;
        $data['user_id'] = $user->id;

        return $data;
    }
}
