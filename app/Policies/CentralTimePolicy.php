<?php

namespace App\Policies;

use App\Models\CentralTime;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CentralTimePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_central::time');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CentralTime $centralTime): bool
    {
        return $user->can('view_central::time');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_central::time');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CentralTime $centralTime): bool
    {
        return $user->can('update_central::time');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CentralTime $centralTime): bool
    {
        return $user->can('delete_central::time');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_central::time');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, CentralTime $centralTime): bool
    {
        return $user->can('force_delete_central::time');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_central::time');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, CentralTime $centralTime): bool
    {
        return $user->can('restore_central::time');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_central::time');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, CentralTime $centralTime): bool
    {
        return $user->can('replicate_central::time');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_central::time');
    }
}
