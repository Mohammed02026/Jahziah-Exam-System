<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Services\Report\ReportService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    public function index(Request $request)
    {
        $attempts = Attempt::query()
            ->where('user_id', auth()->id())
            ->with('exam')
            ->orderByDesc('id')
            ->paginate(15);

        // Analytics مختصر للطالب
        $analytics = $this->reportService->getStudentAnalytics(auth()->id());

        return view('student.results.index', [
            'attempts' => $attempts,
            'analytics' => $analytics,
        ]);
    }

    public function show(Attempt $attempt)
    {
        abort_if($attempt->user_id !== auth()->id(), 403);

        $details = $this->reportService->getAttemptDetails($attempt->id);

        return view('student.results.show', [
            'attempt' => $attempt->load('exam'),
            'details' => $details,
        ]);
    }
}
