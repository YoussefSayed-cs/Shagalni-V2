<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view all users.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * Determine whether the user can view a specific user.
     */
    public function view(User $user, User $target): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * Determine whether the user can update a user.
     */
    public function update(User $user, User $target): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * Determine whether the user can activate or deactivate a user.
     */
    public function toggleActive(User $user, User $target): bool
    {
        return $user->role === UserRole::ADMIN && $user->getKey() !== $target->getKey();
    }
}