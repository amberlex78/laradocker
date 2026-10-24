<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin
            && ($model->is($user) || $model->role !== UserRole::Admin);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin
            && ($model->is($user) || $model->role !== UserRole::Admin);
    }

    public function changeRole(User $user, User $target, UserRole $role): bool
    {
        return $user->role === UserRole::Admin
            && $target->role !== UserRole::Admin
            && $role !== UserRole::Admin;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin
            && ! $model->is($user)
            && $model->role !== UserRole::Admin;
    }

    /**
     * Determine whether the user can restore the target user.
     *
     * User restoration is not enabled for this resource.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the target user.
     *
     * Permanent deletion is not enabled for this resource.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can bulk-delete users.
     *
     * Bulk deletion is disabled so per-record deletion rules cannot be bypassed.
     */
    public function deleteAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can bulk-restore users.
     */
    public function restoreAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can bulk permanently delete users.
     */
    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
