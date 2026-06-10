<?php

namespace App\Services\Report;

use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getSystemSummary(?string $from = null, ?string $to = null): array
    {
        $attempts = Attempt::query();
        if ($from) $attempts->whereDate('created_at', '>=', $from);
        if ($to) $attempts->whereDate('created_at', '<=', $to);

        $attemptCount = (int)$attempts->count();
        $avgScore = (float)$attempts->avg('score');

        return [
            'users' => User::count(),
            'exams' => Exam::count(),
            'questions' => Question::count(),
            'attempts' => $attemptCount,
            'avg_score' => round($avgScore, 2),
        ];
    }

    public function getSystemReport(string $type, ?string $from = null, ?string $to = null): array
    {
        return match ($type) {
            'students' => [
                'items' => User::where('role', 'student')->orderByDesc('id')->limit(200)->get(),
            ],
            'exams' => [
                'items' => Exam::with('course')->orderByDesc('id')->limit(200)->get(),
            ],
            'attempts' => [
                'items' => Attempt::with(['exam', 'student'])
                    ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
                    ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
                    ->orderByDesc('id')->limit(200)->get(),
            ],
            default => [
                'items' => collect(),
                'note' => 'Unknown report type',
            ],
        };
    }

    public function getInstructorResults(int $instructorId, ?int $examId = null, int $perPage = 20): LengthAwarePaginator
    {
        return Attempt::query()
            ->whereHas('exam', function ($q) use ($instructorId, $examId) {
                $q->where('created_by', $instructorId);
                if ($examId) $q->where('id', $examId);
            })
            ->with(['exam', 'student'])
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAttemptDetails(int $attemptId): array
    {
        $attempt = Attempt::with(['exam.questions.options', 'answers.selectedOption', 'answers.question.topic'])
            ->findOrFail($attemptId);

        $answersByQ = $attempt->answers->keyBy('question_id');

        $items = [];
        foreach ($attempt->exam->questions as $q) {
            $ans = $answersByQ->get($q->id);
            $correctOption = $q->options->firstWhere('is_correct', true);

            $items[] = [
                'question_id' => $q->id,
                'question' => $q->body,
                'topic' => $q->topic?->name,
                'marks' => (int)($q->pivot->marks ?? $q->marks),
                'selected_option_id' => $ans?->selected_option_id,
                'selected_text' => $ans?->selectedOption?->text,
                'answer_text' => $ans?->answer_text,
                'is_correct' => (bool)($ans?->is_correct),
                'marks_awarded' => (int)($ans?->marks_awarded ?? 0),
                'correct_option_id' => $correctOption?->id,
                'correct_text' => $correctOption?->text,
            ];
        }

        return [
            'attempt' => $attempt,
            'items' => $items,
        ];
    }

    public function getStudentAnalytics(int $studentId): array
    {
        // تجميع حسب topic و difficulty
        $rows = DB::table('attempt_answers as aa')
            ->join('attempts as a', 'a.id', '=', 'aa.attempt_id')
            ->join('questions as q', 'q.id', '=', 'aa.question_id')
            ->join('topics as t', 't.id', '=', 'q.topic_id')
            ->where('a.user_id', $studentId)
            ->selectRaw('t.name as topic, q.difficulty as difficulty,
                        COUNT(*) as total,
                        SUM(CASE WHEN aa.is_correct = 1 THEN 1 ELSE 0 END) as correct,
                        SUM(aa.marks_awarded) as marks_awarded')
            ->groupBy('t.name', 'q.difficulty')
            ->orderBy('t.name')
            ->get();

        return [
            'by_topic_difficulty' => $rows,
            'attempts' => Attempt::where('user_id', $studentId)->count(),
            'avg_score' => round((float)Attempt::where('user_id', $studentId)->avg('score'), 2),
        ];
    }
}
