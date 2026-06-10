<?php

namespace App\Services\Exam;

use App\Enums\QuestionType;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use Illuminate\Support\Facades\DB;

class GradingService
{
    public function gradeAttempt(int $attemptId): int
    {
        return DB::transaction(function () use ($attemptId) {
            $attempt = Attempt::with(['exam.questions.options', 'answers'])->findOrFail($attemptId);

            $answersByQ = $attempt->answers->keyBy('question_id');

            $score = 0;

            foreach ($attempt->exam->questions as $question) {
                $pivotMarks = (int)($question->pivot->marks ?? $question->marks);
                /** @var AttemptAnswer|null $ans */
                $ans = $answersByQ->get($question->id);

                $isCorrect = false;

                $correctOption = $question->options->firstWhere('is_correct', true);

                if ($question->type === QuestionType::MCQ || $question->type === QuestionType::TrueFalse) {
                    $isCorrect = $ans && $correctOption && $ans->selected_option_id
                        && (int)$ans->selected_option_id === (int)$correctOption->id;
                } else {
                    // ShortAnswer: نستخدم نص الخيار الصحيح لو موجود
                    if ($ans && $correctOption && $ans->answer_text !== null) {
                        $isCorrect = $this->normalize($ans->answer_text) === $this->normalize((string)$correctOption->text);
                    } else {
                        $isCorrect = false;
                    }
                }

                $marksAwarded = $isCorrect ? $pivotMarks : 0;

                if ($ans) {
                    $ans->is_correct = $isCorrect;
                    $ans->marks_awarded = $marksAwarded;
                    $ans->save();
                }

                $score += $marksAwarded;
            }

            $attempt->score = $score;
            $attempt->save();

            return $score;
        });
    }

    private function normalize(string $s): string
    {
        $s = mb_strtolower(trim($s));
        $s = preg_replace('/\s+/', ' ', $s);
        return $s ?? '';
    }
}
