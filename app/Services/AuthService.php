<?php

namespace App\Services;

use App\Models\File;
use App\Models\Record;
use App\Models\Status;
use App\Models\SubProcess;
use App\Models\User;

/**
 * Servicio de Autentificación
 */
class AuthService
{
    public function canApprove(User $user, ?int $subProcessId): bool
    {
        return $user->hasRole('super_admin') || $user->isOwnerOfSubProcess($subProcessId);
    }

    public function canPending(User $user, File $file): bool
    {
        return $user->hasRole('super_admin') || $file->user_id === $user->id;
    }

    public function getOwnerToSubProcess(?int $subProcessId): ?User
    {
        return SubProcess::with('user')->find($subProcessId)?->user;
    }

    public function canAccessSubProcessId(int|string|null $subProcessId): bool
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return true;
        }
        if (is_null($subProcessId)) {
            return false;
        }

        return $user->validSubProcess($subProcessId);
    }

    public function validatedData(array $data, array $preserve = []): array
    {
        $user = auth()->user();
        $record = Record::with('subProcess')->findOrFail($data['record_id']);

        $hasApprovalAccess = $this->canApprove($user, $record->sub_process_id ?? null);
        $statusApproved = Status::byTitle('approved');
        $statusDraft = Status::byTitle('draft');

        $lastVersion = File::where('record_id', $data['record_id'])->orderByDesc('version')->first();

        $newVersion = $lastVersion
            ? ($hasApprovalAccess ? ((int) $lastVersion->version + 1).'.00' : bcadd($lastVersion->version, '0.01', 2))
            : ($hasApprovalAccess ? '1.00' : '0.01');

        return array_merge($data, [
            'status_id' => in_array('status_id', $preserve) ? ($data['status_id'] ?? null) : ($hasApprovalAccess ? $statusApproved->id : $statusDraft->id),
            'version' => in_array('version', $preserve) ? ($data['version'] ?? null) : $newVersion,
            'user_id' => in_array('user_id', $preserve) ? ($data['user_id'] ?? null) : $user->id,
        ]);
    }
}
