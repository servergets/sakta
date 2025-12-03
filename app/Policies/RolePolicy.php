<?php
// app/Policies/RolePolicy.php
namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('role.view');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('role.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('role.create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('role.edit');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermission('role.delete') && $user->role_id !== $role->id;
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->hasPermission('role.edit');
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasPermission('role.delete') && $user->role_id !== $role->id;
    }
}