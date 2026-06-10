<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\Exam\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function __construct(private readonly ExamService $examService)
    {
    }

    public function index()
    {
        $exams = Exam::query()
            ->where('status', 'published')
            ->orderByDesc('id')
            ->paginate(12);

        return view('student.exams.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        abort_if($exam->status !== 'published', 404);

        $exam->loadCount('questions');

        return view('student.exams.show', compact('exam'));
    }

    /**
     * يبدأ الامتحان وينشئ Attempt (إن لم يوجد Attempt مفتوح)
     */
    public function start(Request $request, Exam $exam)
    {
        abort_if($exam->status !== 'published', 404);

        $attempt = $this->examService->startExam(
            examId: $exam->id,
            studentId: auth()->id()
        );

        return redirect()->route('student.exams.take', [$exam, 'attempt' => $attempt->id]);
    }

    /**
     * شاشة الحل (إظهار الأسئلة)
     */
 public function take(Request $request, Exam $exam)
{
    abort_if($exam->status !== 'published', 404);

    $attemptId = (int)$request->query('attempt');
    $vm = $this->examService->getTakeExamViewModel(
        examId: $exam->id,
        attemptId: $attemptId,
        studentId: auth()->id()
    );

    // ✅ تقسيم وقت الاختبار على عدد الأسئلة (بالثواني)
    $questionsCount = (int)($vm['questions']?->count() ?? 0);
    $perQuestionSeconds = (int) floor(((int)$vm['exam']->duration_minutes * 60) / max(1, $questionsCount));

    return view('student.exams.start', [
        'exam' => $vm['exam'],
        'attempt' => $vm['attempt'],
        'questions' => $vm['questions'],
        'endsAt' => $vm['endsAt'],                 // ✅ وقت نهاية الاختبار
        'perQuestionSeconds' => $perQuestionSeconds, // ✅ وقت لكل سؤال
    ]);
}
}
