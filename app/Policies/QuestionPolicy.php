<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    public function view(User $user, Question $question): bool
    {
        if ($user->role === UserRole::Admin) return true;

        if ($user->role === UserRole::Instructor) {
            return (int)$question->created_by === (int)$user->id;
        }

        // الطلاب يشوفون الأسئلة للتدريب/الاختبار (مسموح)
        return $user->role === UserRole::Student;
    }

    public function update(User $user, Question $question): bool
    {
        if ($user->role === UserRole::Admin) return true;
        return $user->role === UserRole::Instructor && (int)$question->created_by === (int)$user->id;
    }

    public function delete(User $user, Question $question): bool
    {
        return $this->update($user, $question);
    }
}
