<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LocationPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('receptionist');
    }

    public function view(User $user, Location $location): bool
    {
        return $user->hasRole('admin') || $user->hasRole('receptionist');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Location $location): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Location $location): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Location $location): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Location $location): bool
    {
        return $user->hasRole('admin');
    }
}
