<?php

namespace App\Services\Exam;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class ExamService
{
    public function createExam(int $instructorId, array $data): Exam
    {
        return DB::transaction(function () use ($instructorId, $data) {

            $payload = [
                'title' => $data['title'],
                'course_id' => (int) $data['course_id'],
                'duration_minutes' => (int) $data['duration_minutes'],
                'status' => $data['status'],
                'created_by' => $instructorId,
                'total_marks' => 0,
            ];

            if (Schema::hasColumn('exams', 'topic_id')) {
                $payload['topic_id'] = $data['topic_id'] ?? null;
            }

            /** @var Exam $exam */
            $exam = Exam::create($payload);

            $this->syncExamStudents($exam, $data);

            return $exam;
        });
    }

    public function updateExam(Exam $exam, int $instructorId, array $data): void
    {
        if ((int) $exam->created_by !== (int) $instructorId) {
            abort(403);
        }

        DB::transaction(function () use ($exam, $data) {
            $exam->title = $data['title'];
            $exam->course_id = (int) $data['course_id'];

            if (Schema::hasColumn('exams', 'topic_id')) {
                $exam->topic_id = $data['topic_id'] ?? null;
            }

            $exam->duration_minutes = (int) $data['duration_minutes'];
            $exam->status = $data['status'];
            $exam->save();

            $this->syncExamStudents($exam, $data);
        });
    }

    public function deleteExam(Exam $exam, int $instructorId): void
    {
        if ((int) $exam->created_by !== (int) $instructorId) {
            abort(403);
        }

        DB::transaction(function () use ($exam) {
            $this->deleteExamStudentRows($exam);

            $exam->questions()->detach();

            $exam->delete();
        });
    }

    public function getExamBuilderViewModel(Exam $exam, int $instructorId, array $filters = [], int $perPage = 10): array
    {
        if ((int) $exam->created_by !== (int) $instructorId) {
            abort(403);
        }

        $selectedQuestions = $exam->questions()
            ->with(['topic'])
            ->withPivot(['order', 'marks'])
            ->orderBy('exam_questions.order')
            ->get();

        $selectedIds = $selectedQuestions->pluck('id')->all();

        $q = Question::query()->with(['topic']);
        $table = (new Question())->getTable();

        if (Schema::hasColumn($table, 'topic_id')) {
            if (Schema::hasColumn('exams', 'topic_id') && !empty($exam->topic_id)) {
                $q->where('topic_id', (int) $exam->topic_id);
            } else {
                $topicIds = Topic::query()
                    ->where('course_id', $exam->course_id)
                    ->pluck('id')
                    ->all();

                if (!empty($topicIds)) {
                    $q->where(function ($w) use ($topicIds) {
                        $w->whereIn('topic_id', $topicIds)
                          ->orWhereNull('topic_id');
                    });
                }
            }
        }

        if (!empty($filters['topic_id']) && Schema::hasColumn($table, 'topic_id')) {
            $q->where('topic_id', (int) $filters['topic_id']);
        }

        if (!empty($filters['difficulty']) && Schema::hasColumn($table, 'difficulty')) {
            $q->where('difficulty', $filters['difficulty']);
        }

        $term = trim((string) ($filters['q'] ?? ''));

        if ($term !== '') {
            $q->where(function ($w) use ($term, $table) {
                $like = "%{$term}%";

                if (Schema::hasColumn($table, 'body')) {
                    $w->orWhere('body', 'like', $like);
                }

                if (Schema::hasColumn($table, 'text')) {
                    $w->orWhere('text', 'like', $like);
                }

                if (Schema::hasColumn($table, 'question_text')) {
                    $w->orWhere('question_text', 'like', $like);
                }

                if (Schema::hasColumn($table, 'title')) {
                    $w->orWhere('title', 'like', $like);
                }
            });
        }

        if (!empty($selectedIds)) {
            $q->whereNotIn('id', $selectedIds);
        }

        $availableQuestions = $q->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $totalMarks = 0;

        foreach ($selectedQuestions as $question) {
            $pivotMarks = (int) ($question->pivot->marks ?? ($question->marks ?? 0));
            $totalMarks += $pivotMarks;
        }

        return [
            'availableQuestions' => $availableQuestions,
            'selectedQuestions'  => $selectedQuestions,
            'selectedCount'      => $selectedQuestions->count(),
            'totalMarks'         => $totalMarks,
            'filters'            => $filters,
        ];
    }

    public function attachQuestion(Exam $exam, int $instructorId, int $questionId, ?int $marks = null): void
    {
        if ((int) $exam->created_by !== $instructorId) {
            throw ValidationException::withMessages([
                'exam' => 'You are not allowed to modify this exam.',
            ]);
        }

        $already = $exam->questions()
            ->where('questions.id', $questionId)
            ->exists();

        if ($already) {
            throw ValidationException::withMessages([
                'question_id' => 'This question is already attached to the exam.',
            ]);
        }

        DB::transaction(function () use ($exam, $questionId, $marks) {
            $nextOrder = (int) DB::table('exam_questions')
                ->where('exam_id', $exam->id)
                ->max('order');

            $exam->questions()->attach($questionId, [
                'marks' => $marks ?? 1,
                'order' => $nextOrder + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->refreshExamTotalMarks($exam);
        });
    }

    public function detachQuestion(Exam $exam, int $instructorId, int $questionId): void
    {
        if ((int) $exam->created_by !== $instructorId) {
            throw ValidationException::withMessages([
                'exam' => 'You are not allowed to modify this exam.',
            ]);
        }

        DB::transaction(function () use ($exam, $questionId) {
            $exam->questions()->detach($questionId);

            $this->refreshExamTotalMarks($exam);
        });
    }

    public function startExam(int $examId, int $studentId): Attempt
    {
        $exam = Exam::query()
            ->withCount('questions')
            ->findOrFail($examId);

        if ($exam->status !== 'published') {
            throw ValidationException::withMessages([
                'exam' => 'هذا الاختبار غير منشور.',
            ]);
        }

        if ((int) $exam->questions_count === 0) {
            throw ValidationException::withMessages([
                'exam' => 'هذا الاختبار لا يحتوي أسئلة.',
            ]);
        }

        if (!$this->studentAllowedForExam($exam, $studentId)) {
            throw ValidationException::withMessages([
                'exam' => 'هذا الاختبار غير مخصص لك.',
            ]);
        }

        $openAttempt = Attempt::query()
            ->where('exam_id', $examId)
            ->where('user_id', $studentId)
            ->where('status', AttemptStatus::InProgress->value)
            ->latest('id')
            ->first();

        if ($openAttempt) {
            return $openAttempt;
        }

        $closedAttempt = Attempt::query()
            ->where('exam_id', $examId)
            ->where('user_id', $studentId)
            ->whereIn('status', [
                AttemptStatus::Submitted->value,
                AttemptStatus::Graded->value,
            ])
            ->latest('id')
            ->first();

        if ($closedAttempt) {
            throw ValidationException::withMessages([
                'exam' => 'لقد قمت بتسليم هذا الاختبار مسبقًا.',
            ]);
        }

        return DB::transaction(function () use ($examId, $studentId) {
            return Attempt::create([
                'exam_id'    => $examId,
                'user_id'    => $studentId,
                'status'     => AttemptStatus::InProgress,
                'started_at' => now(),
                'score'      => 0,
            ]);
        });
    }

    public function getTakeExamViewModel(int $examId, int $attemptId, int $studentId): array
    {
        $attempt = Attempt::query()
            ->with([
                'exam',
                'exam.questions' => function ($q) {
                    $q->with(['options', 'topic']);
                },
                'answers',
            ])
            ->findOrFail($attemptId);

        if ((int) $attempt->exam_id !== (int) $examId) {
            abort(404);
        }

        if ((int) $attempt->user_id !== (int) $studentId) {
            abort(403);
        }

        if ($attempt->status !== AttemptStatus::InProgress) {
            abort(422, 'Attempt is not open.');
        }

        /** @var Exam $exam */
        $exam = $attempt->exam;

        $questions = $exam->questions()
            ->with(['options', 'topic'])
            ->orderBy('exam_questions.order')
            ->get();

        $endsAt = $attempt->started_at
            ? $attempt->started_at->copy()->addMinutes((int) $exam->duration_minutes)
            : null;

        return [
            'attempt' => $attempt,
            'exam' => $exam,
            'questions' => $questions,
            'endsAt' => $endsAt,
        ];
    }

    public function reorderQuestions(Exam $exam, int $instructorId, array $orders): void
    {
        if ((int) $exam->created_by !== (int) $instructorId) {
            throw ValidationException::withMessages([
                'exam' => 'You are not allowed to modify this exam.',
            ]);
        }

        DB::transaction(function () use ($exam, $orders) {
            foreach ($orders as $row) {
                $qid = (int) ($row['question_id'] ?? 0);
                $ord = (int) ($row['order'] ?? 0);

                if ($qid <= 0 || $ord <= 0) {
                    continue;
                }

                DB::table('exam_questions')
                    ->where('exam_id', $exam->id)
                    ->where('question_id', $qid)
                    ->update([
                        'order' => $ord,
                        'updated_at' => now(),
                    ]);
            }

            $rows = DB::table('exam_questions')
                ->where('exam_id', $exam->id)
                ->orderBy('order')
                ->orderBy('question_id')
                ->get(['question_id']);

            $i = 1;

            foreach ($rows as $r) {
                DB::table('exam_questions')
                    ->where('exam_id', $exam->id)
                    ->where('question_id', $r->question_id)
                    ->update([
                        'order' => $i++,
                        'updated_at' => now(),
                    ]);
            }

            $this->refreshExamTotalMarks($exam);
        });
    }

    private function refreshExamTotalMarks(Exam $exam): void
    {
        $exam->total_marks = (int) DB::table('exam_questions')
            ->where('exam_id', $exam->id)
            ->sum('marks');

        $exam->save();
    }

    private function syncExamStudents(Exam $exam, array $data): void
    {
        $assignTo = $data['assign_to'] ?? 'all';

        if (!$this->examStudentsTableExists()) {
            return;
        }

        $table = $this->examStudentsTableName();

        DB::table($table)
            ->where('exam_id', $exam->id)
            ->delete();

        if ($assignTo !== 'selected') {
            return;
        }

        $studentIds = array_unique(array_map('intval', $data['student_ids'] ?? []));

        foreach ($studentIds as $studentId) {
            DB::table($table)->insert([
                'exam_id' => $exam->id,
                'user_id' => $studentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function studentAllowedForExam(Exam $exam, int $studentId): bool
    {
        if (!$this->examStudentsTableExists()) {
            return true;
        }

        $table = $this->examStudentsTableName();

        $hasSpecificStudents = DB::table($table)
            ->where('exam_id', $exam->id)
            ->exists();

        if (!$hasSpecificStudents) {
            return true;
        }

        return DB::table($table)
            ->where('exam_id', $exam->id)
            ->where('user_id', $studentId)
            ->exists();
    }

    private function deleteExamStudentRows(Exam $exam): void
    {
        if (!$this->examStudentsTableExists()) {
            return;
        }

        DB::table($this->examStudentsTableName())
            ->where('exam_id', $exam->id)
            ->delete();
    }

    private function examStudentsTableExists(): bool
    {
        return $this->examStudentsTableName() !== null;
    }

    private function examStudentsTableName(): ?string
    {
        foreach (['exam_students', 'exam_user', 'exam_student'] as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }
}