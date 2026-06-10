<?php

namespace App\Services\Exam;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class AttemptService
{
    public function __construct(private readonly GradingService $gradingService)
    {
    }

    public function submitAttempt(int $attemptId, int $studentId, array $answers): void
    {
        DB::transaction(function () use ($attemptId, $studentId, $answers) {
            $attempt = Attempt::with('exam.questions')->findOrFail($attemptId);

            if ((int)$attempt->user_id !== (int)$studentId) abort(403);

            if ($attempt->status !== AttemptStatus::InProgress) {
                abort(422, 'Attempt is not open.');
            }

            // تحقق وقت الامتحان (server-side)
            $exam = $attempt->exam;
            $endsAt = $attempt->started_at->copy()->addMinutes((int)$exam->duration_minutes);
            if (now()->greaterThan($endsAt)) {
                // نسمح بالإرسال لكن تعتبر منتهية (هذا قرار تصميم)
                // يمكنك جعلها abort(422) لو تريد منع الإرسال بعد الوقت.
            }

            // حفظ/تحديث الإجابات
            foreach ($answers as $questionId => $row) {
                $qid = (int)$questionId;

                // فقط أسئلة الامتحان
                if (!$attempt->exam->questions->contains('id', $qid)) continue;

                AttemptAnswer::updateOrCreate(
                    ['attempt_id' => $attempt->id, 'question_id' => $qid],
                    [
                        'selected_option_id' => isset($row['selected_option_id']) && $row['selected_option_id'] !== ''
                            ? (int)$row['selected_option_id']
                            : null,
                        'answer_text' => $row['answer_text'] ?? null,
                    ]
                );
            }

            $attempt->status = AttemptStatus::Submitted;
            $attempt->submitted_at = now();
            $attempt->save();
        });

        // التصحيح خارج الترانزاكشن الأولى
        $this->gradingService->gradeAttempt($attemptId);

        Attempt::where('id', $attemptId)->update(['status' => AttemptStatus::Graded->value]);
    }
}