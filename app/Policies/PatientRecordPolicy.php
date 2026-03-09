<?php

namespace App\Policies;

use App\Models\PatientRecord;
use App\Models\User;

class PatientRecordPolicy
{
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

    public function view(User $user, PatientRecord $patientRecord): bool
    {
        return $user->hasRole('admin') || $user->hasRole('receptionist');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('receptionist');
    }

    public function update(User $user, PatientRecord $patientRecord): bool
    {
        return $user->hasRole('admin') || $user->hasRole('receptionist');
    }

    public function delete(User $user, PatientRecord $patientRecord): bool
    {
        return $user->hasRole('admin');
    }
}
