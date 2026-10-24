<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can access the user management resource.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can view the target user.
     */
    public function view(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin;
    }

    /**
     * Determine whether the user can create a user account.
     *
     * The form limits assignments to user and operator roles.
     */
    public function create(User $user): bool
    {
        return $user->role->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can update the target user.
     *
     * Administrators can update users and operators, as well as their own
     * administrator account.
     */
    public function update(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin;
    }

    /**
     * Determine whether the user can delete the target user.
     *
     * Administrators cannot delete administrator accounts or themselves.
     */
    public function delete(User $user, User $model): bool
    {
        return ! $model->is($user);
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
