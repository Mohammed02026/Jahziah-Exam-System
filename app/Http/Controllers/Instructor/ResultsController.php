<?php

namespace App\Http\Controllers\Instructor;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use App\Services\Report\ReportService;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    public function index(Request $request)
    {
        $examId = $request->query('exam_id');

        $exams = Exam::where('created_by', auth()->id())
            ->orderByDesc('id')
            ->get();

        $results = $this->reportService->getInstructorResults(
            instructorId: auth()->id(),
            examId: $examId ? (int)$examId : null,
            perPage: 20
        );

        return view('instructor.results.index', [
            'exams' => $exams,
            'examId' => $examId,
            'results' => $results,
        ]);
    }

    public function show(Attempt $attempt)
    {
        $this->authorize('view', $attempt);

        $details = $this->reportService->getAttemptDetails($attempt->id);

        return view('instructor.results.show', [
            'attempt' => $attempt,
            'details' => $details,
        ]);
    }

    public function studentReports(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $students = User::where('role', UserRole::Student->value)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $selectedStudent = null;
        $attempts = collect();
        $summary = null;

        if ($request->filled('student_id')) {
            $selectedStudent = User::where('role', UserRole::Student->value)
                ->findOrFail($request->student_id);

            $attempts = Attempt::with(['exam.course', 'exam.topic', 'exam.questions.topic'])
                ->where('user_id', $selectedStudent->id)
                ->whereHas('exam', function ($q) {
                    $q->where('created_by', auth()->id());
                })
                ->orderByDesc('id')
                ->get();

            $totalAttempts = $attempts->count();

            $passed = $attempts->filter(function ($attempt) {
                return $this->attemptPercentage($attempt) >= 60;
            })->count();

            $failed = $attempts->filter(function ($attempt) {
                return $this->attemptPercentage($attempt) < 60;
            })->count();

            $summary = [
                'total_attempts' => $totalAttempts,
                'passed' => $passed,
                'failed' => $failed,
                'average' => $totalAttempts > 0 ? round($attempts->avg(fn ($a) => $this->attemptPercentage($a)), 2) : 0,
                'highest' => $totalAttempts > 0 ? round($attempts->max(fn ($a) => $this->attemptPercentage($a)), 2) : 0,
                'lowest' => $totalAttempts > 0 ? round($attempts->min(fn ($a) => $this->attemptPercentage($a)), 2) : 0,
            ];
        }

        return view('instructor.results.student_reports', [
            'students' => $students,
            'search' => $search,
            'selectedStudent' => $selectedStudent,
            'attempts' => $attempts,
            'summary' => $summary,
        ]);
    }

    public function examReports(Request $request)
    {
        $selectedExamId = $request->query('exam_id');

        $exams = Exam::with(['course', 'topic'])
            ->where('created_by', auth()->id())
            ->orderByDesc('id')
            ->get();

        $selectedExam = null;
        $attempts = collect();
        $summary = null;

        if ($selectedExamId) {
            $selectedExam = Exam::with(['course', 'topic'])
                ->where('created_by', auth()->id())
                ->findOrFail($selectedExamId);

            $attempts = Attempt::with('student')
                ->where('exam_id', $selectedExam->id)
                ->orderByDesc('score')
                ->get();

            $total = $attempts->count();

            $passed = $attempts->filter(function ($attempt) {
                return $this->attemptPercentage($attempt) >= 60;
            })->count();

            $failed = $attempts->filter(function ($attempt) {
                return $this->attemptPercentage($attempt) < 60;
            })->count();

            $excellent = $attempts->filter(function ($attempt) {
                return $this->attemptPercentage($attempt) >= 90;
            })->count();

            $summary = [
                'total' => $total,
                'passed' => $passed,
                'failed' => $failed,
                'excellent' => $excellent,
                'pass_rate' => $total > 0 ? round(($passed / $total) * 100, 2) : 0,
                'fail_rate' => $total > 0 ? round(($failed / $total) * 100, 2) : 0,
                'excellent_rate' => $total > 0 ? round(($excellent / $total) * 100, 2) : 0,
                'average' => $total > 0 ? round($attempts->avg(fn ($a) => $this->attemptPercentage($a)), 2) : 0,
                'highest' => $total > 0 ? round($attempts->max(fn ($a) => $this->attemptPercentage($a)), 2) : 0,
                'lowest' => $total > 0 ? round($attempts->min(fn ($a) => $this->attemptPercentage($a)), 2) : 0,
            ];
        }

        return view('instructor.results.exam_reports', [
            'exams' => $exams,
            'selectedExamId' => $selectedExamId,
            'selectedExam' => $selectedExam,
            'attempts' => $attempts,
            'summary' => $summary,
        ]);
    }

    private function attemptPercentage(Attempt $attempt): float
    {
        $totalMarks = (int) ($attempt->exam?->total_marks ?? 0);

        if ($totalMarks <= 0) {
            return 0;
        }

        return round(((int) $attempt->score / $totalMarks) * 100, 2);
    }
}