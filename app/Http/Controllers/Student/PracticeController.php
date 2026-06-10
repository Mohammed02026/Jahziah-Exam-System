<?php

namespace App\Http\Controllers\Student;

use App\Enums\Difficulty;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Services\Question\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PracticeController extends Controller
{
    public function __construct(private readonly QuestionService $questionService)
    {
    }

    public function index(Request $request)
    {
        $filters = [
            'q' => $request->query('q'),
            'topic_id' => $request->query('topic_id'),
            'difficulty' => $request->query('difficulty'),
            'type' => $request->query('type'),
        ];

        $questions = $this->questionService->paginateForPractice($filters, 12);

        // عرض جميع المحاور مع اسم الكورس
        $topics = Topic::with('course')
            ->orderBy('course_id')
            ->orderBy('name')
            ->get();

        $selectedTopic = !empty($filters['topic_id'])
            ? Topic::with('course')->find($filters['topic_id'])
            : null;

        return view('student.practice.index', [
            'questions' => $questions,
            'topics' => $topics,
            'difficultyOptions' => Difficulty::options(),
            'filters' => $filters,
            'selectedTopic' => $selectedTopic,
        ]);
    }

    /**
     * Practice session: one random question based on filters
     */
    public function session(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['nullable', 'integer', 'exists:topics,id'],
            'difficulty' => ['nullable', Rule::in(Difficulty::values())],
            'question_id' => ['nullable', 'integer'],
        ]);

        $topicId = $data['topic_id'] ?? null;
        $difficulty = $data['difficulty'] ?? null;
        $excludeQuestionId = $data['question_id'] ?? null;

        $question = $this->questionService->getRandomQuestionForPractice(
            topicId: $topicId,
            difficulty: $difficulty,
            excludeQuestionId: $excludeQuestionId
        );

        if (!$question) {
            return redirect()->route('student.practice.index', [
                'topic_id' => $topicId,
                'difficulty' => $difficulty,
            ])->with('error', 'No practice questions found for the selected topic.');
        }

        $question->load(['topic.course', 'options']);

        return view('student.practice.session', [
            'question' => $question,
            'topicId' => $topicId,
            'difficulty' => $difficulty,
        ]);
    }

    /**
     * Check practice answer with instant feedback
     */
    public function check(Request $request)
    {
        $data = $request->validate([
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'selected_option_id' => ['required', 'integer'],
        ]);

        $result = $this->questionService->checkPracticeAnswer(
            questionId: (int) $data['question_id'],
            selectedOptionId: (int) $data['selected_option_id']
        );

        return back()->with('practice_result', $result);
    }
}