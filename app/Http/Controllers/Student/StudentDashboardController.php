<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Exam;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $studentId = auth()->id();

        // الاختبارات المتاحة: منشورة + فيها أسئلة
        $availableExamsCount = Exam::query()
            ->where('status', 'published')
            ->whereHas('questions')   // مهم: لا يعتبر اختبار بدون أسئلة "متاح"
            ->count();

        // عدد محاولات الطالب
        $attemptsCount = Attempt::query()
            ->where('user_id', $studentId)
            ->count();

        // آخر النتائج (محاولات تم تسليمها/عندها submitted_at)
        $recentAttempts = Attempt::query()
            ->with('exam')
            ->where('user_id', $studentId)
            ->whereNotNull('submitted_at')
            ->orderByDesc('submitted_at')
            ->limit(10)
            ->get();

        $counts = [
            'available_exams' => $availableExamsCount,
            'attempts' => $attemptsCount,
        ];

        return view('student.dashboard', compact('counts', 'recentAttempts'));
    }
}