<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    public function index(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $summary = $this->reportService->getSystemSummary($from, $to);

        return view('admin.reports.index', [
            'summary' => $summary,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function show(Request $request, string $type)
    {
        // مثال: type = "students" / "exams" / "attempts"
        $from = $request->query('from');
        $to   = $request->query('to');

        $report = $this->reportService->getSystemReport($type, $from, $to);

        return view('admin.reports.show', [
            'type' => $type,
            'report' => $report,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
