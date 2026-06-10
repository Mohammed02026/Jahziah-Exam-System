<?php

namespace App\Services\Question;

use App\Enums\QuestionType;
use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuestionService
{
    public function paginateForInstructor(int $instructorId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Question::query()
            ->where('created_by', $instructorId)
            ->with('topic');

        $this->applyFilters($q, $filters);

        return $q->orderByDesc('id')->paginate($perPage)->withQueryString();
    }

    public function paginateForPractice(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $q = Question::query()->with('topic');

        $this->applyFilters($q, $filters);

        return $q->orderByDesc('id')->paginate($perPage)->withQueryString();
    }

    public function getRandomQuestionForPractice(?int $topicId = null, ?string $difficulty = null, ?int $excludeQuestionId = null): ?Question
    {
        $q = Question::query();

        if ($topicId) {
            $q->where('topic_id', $topicId);
        }

        if ($difficulty) {
            $q->where('difficulty', $difficulty);
        }

        if ($excludeQuestionId) {
            $q->where('id', '!=', $excludeQuestionId);
        }

        return $q->inRandomOrder()->first();
    }

    public function checkPracticeAnswer(int $questionId, ?int $selectedOptionId, ?string $answerText = null): array
    {
        $question = Question::with('options')->findOrFail($questionId);

        $correctOption = $question->options->firstWhere('is_correct', true);

        $isCorrect = $correctOption
            && $selectedOptionId
            && (int) $correctOption->id === (int) $selectedOptionId;

        return [
            'question_id' => $question->id,
            'is_correct' => $isCorrect,
            'correct_option_id' => $correctOption?->id,
            'correct_text' => $correctOption?->text,
            'marks' => (int) $question->marks,
        ];
    }

    public function createQuestion(int $instructorId, array $payload): Question
    {
        return DB::transaction(function () use ($instructorId, $payload) {
            /** @var Question $question */
            $question = Question::create([
                'topic_id' => (int) $payload['topic_id'],
                'body' => $payload['body'],
                'type' => $payload['type'],
                'difficulty' => $payload['difficulty'],
                'learning_domain' => $payload['learning_domain'] ?? 'knowledge',
                'marks' => (int) $payload['marks'],
                'created_by' => $instructorId,
            ]);

            $this->syncOptions($question, $payload['options'] ?? []);

            return $question;
        });
    }

    public function updateQuestion(Question $question, int $instructorId, array $payload): Question
    {
        return DB::transaction(function () use ($question, $instructorId, $payload) {
            if ((int) $question->created_by !== (int) $instructorId) {
                abort(403);
            }

            $question->update([
                'topic_id' => (int) $payload['topic_id'],
                'body' => $payload['body'],
                'type' => $payload['type'],
                'difficulty' => $payload['difficulty'],
                'learning_domain' => $payload['learning_domain'] ?? 'knowledge',
                'marks' => (int) $payload['marks'],
            ]);

            $this->syncOptions($question, $payload['options'] ?? [], true);

            return $question;
        });
    }

    public function deleteQuestion(Question $question, int $instructorId): void
    {
        if ((int) $question->created_by !== (int) $instructorId) {
            abort(403);
        }

        DB::transaction(function () use ($question) {
            $question->options()->delete();
            $question->delete();
        });
    }

    private function applyFilters(Builder $q, array $filters): void
    {
        $search = trim((string) ($filters['q'] ?? ''));
        $topicId = $filters['topic_id'] ?? null;
        $difficulty = $filters['difficulty'] ?? null;
        $type = $filters['type'] ?? null;
        $learningDomain = $filters['learning_domain'] ?? null;

        if ($search !== '') {
            $q->where('body', 'like', "%{$search}%");
        }

        if (!empty($topicId)) {
            $q->where('topic_id', (int) $topicId);
        }

        if (!empty($difficulty)) {
            $q->where('difficulty', (string) $difficulty);
        }

        if (!empty($type)) {
            $q->where('type', (string) $type);
        }

        if (!empty($learningDomain)) {
            $q->where('learning_domain', (string) $learningDomain);
        }
    }

    private function syncOptions(Question $question, array $options, bool $updating = false): void
    {
        $type = $question->type instanceof QuestionType
            ? $question->type->value
            : (string) $question->type;

        if (!in_array($type, [QuestionType::MCQ->value, QuestionType::TrueFalse->value], true)) {
            throw ValidationException::withMessages([
                'type' => 'Invalid question type.',
            ]);
        }

        $options = array_values(array_filter($options, function ($option) {
            $text = trim((string) ($option['text'] ?? ''));

            return $text !== '';
        }));

        if ($type === QuestionType::TrueFalse->value && empty($options)) {
            $options = [
                ['text' => 'True', 'is_correct' => true],
                ['text' => 'False', 'is_correct' => false],
            ];
        }

        if ($type === QuestionType::MCQ->value && count($options) < 2) {
            throw ValidationException::withMessages([
                'options' => 'Multiple Choice question must have at least two options.',
            ]);
        }

        if ($type === QuestionType::TrueFalse->value) {
            $options = array_slice($options, 0, 2);
        }

        $correctCount = 0;

        foreach ($options as $option) {
            if (!empty($option['is_correct'])) {
                $correctCount++;
            }
        }

        if ($correctCount !== 1) {
            throw ValidationException::withMessages([
                'options' => 'Exactly one correct option is required.',
            ]);
        }

        if ($updating) {
            $question->options()->delete();
        }

        foreach ($options as $option) {
            $question->options()->create([
                'text' => trim((string) $option['text']),
                'is_correct' => (bool) ($option['is_correct'] ?? false),
            ]);
        }
    }
}