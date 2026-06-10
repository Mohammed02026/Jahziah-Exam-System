<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Topic;
use App\Models\User;
use App\Services\Exam\ExamService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamController extends Controller
{
    public function __construct(private readonly ExamService $examService)
    {
    }

   public function index()
{
    $exams = Exam::where('created_by', auth()->id())
        ->with(['course', 'topic.course'])
        ->orderByDesc('id')
        ->paginate(15);

    return view('instructor.exams.index', compact('exams'));
}

    public function create()
    {
        return view('instructor.exams.create', [
            'courses' => Course::orderBy('name')->get(),
            'topics' => Topic::orderBy('name')->get(),
            'students' => User::where('role', 'student')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'topic_id' => ['required', 'integer', 'exists:topics,id'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:300'],
            'status' => ['required', Rule::in(['draft', 'published'])],

            'assign_to' => ['required', Rule::in(['all', 'selected'])],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:users,id'],
        ]);

        if ($data['assign_to'] === 'selected' && empty($data['student_ids'])) {
            return back()
                ->withErrors([
                    'student_ids' => 'Please select at least one student.',
                ])
                ->withInput();
        }

        $exam = $this->examService->createExam(auth()->id(), $data);

        return redirect()
            ->route('instructor.exams.show', $exam)
            ->with('success', 'Exam created.');
    }

   public function show(Exam $exam)
{
    $this->authorize('view', $exam);

    $exam->load(['course', 'topic.course', 'questions']);

    return view('instructor.exams.show', compact('exam'));
}

    public function edit(Exam $exam)
    {
        $this->authorize('update', $exam);

        return view('instructor.exams.edit', [
            'exam' => $exam,
            'courses' => Course::orderBy('name')->get(),
            'topics' => Topic::orderBy('name')->get(),
            'students' => User::where('role', 'student')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'topic_id' => ['required', 'integer', 'exists:topics,id'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:300'],
            'status' => ['required', Rule::in(['draft', 'published'])],

            'assign_to' => ['required', Rule::in(['all', 'selected'])],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:users,id'],
        ]);

        if ($data['assign_to'] === 'selected' && empty($data['student_ids'])) {
            return back()
                ->withErrors([
                    'student_ids' => 'Please select at least one student.',
                ])
                ->withInput();
        }

        $this->examService->updateExam($exam, auth()->id(), $data);

        return redirect()
            ->route('instructor.exams.show', $exam)
            ->with('success', 'Exam updated.');
    }

    public function destroy(Exam $exam)
    {
        $this->authorize('delete', $exam);

        $this->examService->deleteExam($exam, auth()->id());

        return redirect()
            ->route('instructor.exams.index')
            ->with('success', 'Exam deleted.');
    }
}