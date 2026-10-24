<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can access the user management resource.
     *
     * Only administrators and developers can access the admin panel.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can view the target user.
     *
     * Administrators cannot see developers, while developers can view all users.
     */
    public function view(User $user, User $model): bool
    {
        return match ($user->role) {
            UserRole::Admin => $model->role !== UserRole::Developer,
            UserRole::Developer => true,
            default => false,
        };
    }

    /**
     * Determine whether the user can create a user account.
     *
     * The form separately limits which roles the current user can assign.
     */
    public function create(User $user): bool
    {
        return $user->role->canAccessAdminPanel();
    }

    /**
     * Determine whether the user can update the target user.
     *
     * The current user's role defines which target roles are manageable.
     * An administrator may also update their own administrator account.
     * Other administrator accounts are view-only.
     */
    public function update(User $user, User $model): bool
    {
        return in_array($model->role, $user->role->assignableRoles(), true)
            || ($user->role === UserRole::Admin
                && $model->id === $user->id
                && $model->role === UserRole::Admin);
    }

    /**
     * Determine whether the user can delete the target user.
     *
     * A user cannot delete their own account or another administrator account
     * because administrators cannot assign or manage the administrator role.
     */
    public function delete(User $user, User $model): bool
    {
        return in_array($model->role, $user->role->assignableRoles(), true);
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
