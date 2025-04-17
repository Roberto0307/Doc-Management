<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    public function canApprove(User $user, ?int $subProcessId): bool
    {
        return $user->hasRole('super_admin') ||
               ($user->hasRole('pro') && $user->validSubProcess($subProcessId));
    }
}
