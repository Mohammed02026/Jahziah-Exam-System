<?php

namespace App\Http\Controllers\Instructor;

use App\Enums\Difficulty;
use App\Enums\QuestionType;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Topic;
use App\Services\Question\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
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
            'learning_domain' => $request->query('learning_domain'),
        ];

        $questions = $this->questionService->paginateForInstructor(
            instructorId: auth()->id(),
            filters: $filters,
            perPage: 15
        );

        // حتى يظهر اسم المقرر مع الموضوع مثل: Data Structures – Trees
        $questions->getCollection()->load(['topic.course']);

        return view('instructor.questions.index', [
            'questions' => $questions,
            'topics' => Topic::with('course')
                ->orderBy('course_id')
                ->orderBy('name')
                ->get(),
            'difficultyOptions' => Difficulty::options(),
            'typeOptions' => QuestionType::options(),
            'learningDomainOptions' => [
                'knowledge' => 'Knowledge',
                'skills' => 'Skills',
            ],
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('instructor.questions.create', [
            'topics' => Topic::with('course')
                ->orderBy('course_id')
                ->orderBy('name')
                ->get(),
            'difficultyOptions' => Difficulty::options(),
            'typeOptions' => QuestionType::options(),
            'learningDomainOptions' => [
                'knowledge' => 'Knowledge',
                'skills' => 'Skills',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'integer', 'exists:topics,id'],
            'body' => ['required', 'string', 'max:2000'],
            'type' => ['required', Rule::in(QuestionType::values())],
            'difficulty' => ['required', Rule::in(Difficulty::values())],
            'learning_domain' => ['required', Rule::in(['knowledge', 'skills'])],
            'marks' => ['required', 'integer', 'min:1', 'max:100'],

            'options' => ['required', 'array'],
            'options.*.text' => ['nullable', 'string', 'max:255'],
            'options.*.is_correct' => ['nullable', 'boolean'],
        ], [
            'topic_id.required' => 'Please select a topic.',
            'body.required' => 'Please write the question text.',
            'type.required' => 'Please select the question type.',
            'difficulty.required' => 'Please select the difficulty level.',
            'learning_domain.required' => 'Please select the learning domain.',
            'marks.required' => 'Please enter the question marks.',
            'options.required' => 'Please enter the answer options.',
        ]);

        $this->questionService->createQuestion(
            instructorId: auth()->id(),
            payload: $data
        );

        return redirect()
            ->route('instructor.questions.index')
            ->with('success', 'Question created.');
    }

    public function show(Question $question)
    {
        $this->authorize('view', $question);

        $question->load(['topic.course', 'options']);

        return view('instructor.questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $this->authorize('update', $question);

        $question->load(['options', 'topic.course']);

        return view('instructor.questions.edit', [
            'question' => $question,
            'topics' => Topic::with('course')
                ->orderBy('course_id')
                ->orderBy('name')
                ->get(),
            'difficultyOptions' => Difficulty::options(),
            'typeOptions' => QuestionType::options(),
            'learningDomainOptions' => [
                'knowledge' => 'Knowledge',
                'skills' => 'Skills',
            ],
        ]);
    }

    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);

        $data = $request->validate([
            'topic_id' => ['required', 'integer', 'exists:topics,id'],
            'body' => ['required', 'string', 'max:2000'],
            'type' => ['required', Rule::in(QuestionType::values())],
            'difficulty' => ['required', Rule::in(Difficulty::values())],
            'learning_domain' => ['required', Rule::in(['knowledge', 'skills'])],
            'marks' => ['required', 'integer', 'min:1', 'max:100'],

            'options' => ['required', 'array'],
            'options.*.id' => ['nullable', 'integer'],
            'options.*.text' => ['nullable', 'string', 'max:255'],
            'options.*.is_correct' => ['nullable', 'boolean'],
        ], [
            'topic_id.required' => 'Please select a topic.',
            'body.required' => 'Please write the question text.',
            'type.required' => 'Please select the question type.',
            'difficulty.required' => 'Please select the difficulty level.',
            'learning_domain.required' => 'Please select the learning domain.',
            'marks.required' => 'Please enter the question marks.',
            'options.required' => 'Please enter the answer options.',
        ]);

        $this->questionService->updateQuestion(
            question: $question,
            instructorId: auth()->id(),
            payload: $data
        );

        return redirect()
            ->route('instructor.questions.index')
            ->with('success', 'Question updated.');
    }

    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);

        $this->questionService->deleteQuestion($question, auth()->id());

        return redirect()
            ->route('instructor.questions.index')
            ->with('success', 'Question deleted.');
    }
}