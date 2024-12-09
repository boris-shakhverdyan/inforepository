<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Role $role): bool
    {
        return $user->is_admin && ! $role->is_for_system;
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->is_admin && ! $role->is_for_system;
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->is_admin && ! $role->is_for_system;
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->is_admin && ! $role->is_for_system;
    }
}
