<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Lead $lead): bool
    {
        return $user->role === UserRole::ADMIN
            || $lead->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            UserRole::ADMIN,
            UserRole::SALES,
        ], true);
    }

    public function update(User $user, Lead $lead): bool
    {
        return $user->role === UserRole::ADMIN
            || $lead->assigned_to === $user->id;
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function assign(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}
