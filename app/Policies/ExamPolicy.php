<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    public function view(User $user, Exam $exam): bool
    {
        // Admin يشوف الكل
        if ($user->role === UserRole::Admin) return true;

        // Instructor يشوف امتحاناته
        if ($user->role === UserRole::Instructor) {
            return (int)$exam->created_by === (int)$user->id;
        }

        // Student يشوف المنشور فقط
        if ($user->role === UserRole::Student) {
            return $exam->status === 'published';
        }

        return false;
    }

    public function update(User $user, Exam $exam): bool
    {
        if ($user->role === UserRole::Admin) return true;
        return $user->role === UserRole::Instructor && (int)$exam->created_by === (int)$user->id;
    }

    public function delete(User $user, Exam $exam): bool
    {
        return $this->update($user, $exam);
    }
}
