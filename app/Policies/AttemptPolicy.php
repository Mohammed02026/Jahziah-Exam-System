<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Attempt;
use App\Models\User;

class AttemptPolicy
{
    public function view(User $user, Attempt $attempt): bool
    {
        if ($user->role === UserRole::Admin) return true;

        // Student يشوف محاولاته
        if ($user->role === UserRole::Student) {
            return (int)$attempt->user_id === (int)$user->id;
        }

        // Instructor يشوف محاولات امتحاناته
        if ($user->role === UserRole::Instructor) {
            return (int)$attempt->exam?->created_by === (int)$user->id;
        }

        return false;
    }
}
