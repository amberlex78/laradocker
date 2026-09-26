<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return in_array($actor->role, [UserRole::Developer, UserRole::Admin], true);
    }

    public function view(User $actor, User $target): bool
    {
        return $actor->role === UserRole::Developer
            || ($actor->role === UserRole::Admin && $target->role !== UserRole::Developer);
    }

    public function create(User $actor, UserRole $role): bool
    {
        return $actor->role === UserRole::Developer
            || ($actor->role === UserRole::Admin && $role !== UserRole::Developer);
    }

    public function update(User $actor, User $target): bool
    {
        return $actor->role === UserRole::Developer
            || ($actor->role === UserRole::Admin && $target->role !== UserRole::Developer);
    }

    public function changeRole(User $actor, User $target, UserRole $role): bool
    {
        if ($actor->role === UserRole::Developer) {
            return true;
        }

        if ($actor->role !== UserRole::Admin || $this->isSameUser($actor, $target)) {
            return false;
        }

        if ($target->role === UserRole::Developer || $role === UserRole::Developer) {
            return false;
        }

        return $target->role !== UserRole::Admin || $role === UserRole::Admin;
    }

    public function delete(User $actor, User $target): bool
    {
        if ($this->isSameUser($actor, $target)) {
            return false;
        }

        return $actor->role === UserRole::Developer
            || ($actor->role === UserRole::Admin && in_array($target->role, [
                UserRole::Operator,
                UserRole::User,
            ], true));
    }

    private function isSameUser(User $actor, User $target): bool
    {
        return $actor === $target
            || ($actor->getKey() !== null && $actor->is($target));
    }
}
