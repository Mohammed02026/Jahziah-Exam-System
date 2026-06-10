<?php

namespace App\Http\Controllers\Instructor;

use App\Enums\Difficulty;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Topic;
use App\Services\Exam\ExamService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamBuilderController extends Controller
{
    public function __construct(private readonly ExamService $examService)
    {
    }

    public function index(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $filters = [
            'q' => $request->query('q'),
            'topic_id' => $request->query('topic_id'),
            'difficulty' => $request->query('difficulty'),
        ];

        $vm = $this->examService->getExamBuilderViewModel(
            exam: $exam,
            instructorId: auth()->id(),
            filters: $filters,
            perPage: 10
        );

        return view('instructor.builder.index', [
            'exam' => $exam,
            'vm' => $vm,
            'topics' => Topic::orderBy('name')->get(),
            'difficultyOptions' => Difficulty::options(),
            'filters' => $filters,
        ]);
    }

    public function attach(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $data = $request->validate([
            'question_id' => ['required','integer','exists:questions,id'],
            'marks' => ['nullable','integer','min:1','max:100'],
        ]);

        $this->examService->attachQuestion($exam, auth()->id(), (int)$data['question_id'], $data['marks'] ?? null);

        return back()->with('success', 'Question added to exam.');
    }

    public function detach(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $data = $request->validate([
            'question_id' => ['required','integer','exists:questions,id'],
        ]);

        $this->examService->detachQuestion($exam, auth()->id(), (int)$data['question_id']);

        return back()->with('success', 'Question removed from exam.');
    }

    public function reorder(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $data = $request->validate([
            'orders' => ['required','array'],
            'orders.*.question_id' => ['required','integer','exists:questions,id'],
            'orders.*.order' => ['required','integer','min:1','max:500'],
        ]);

        $this->examService->reorderQuestions($exam, auth()->id(), $data['orders']);

        return back()->with('success', 'Order updated.');
    }
}
