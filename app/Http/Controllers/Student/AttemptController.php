<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Services\Exam\AttemptService;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function __construct(private readonly AttemptService $attemptService)
    {
    }

    /**
     * إرسال المحاولة (حفظ الإجابات + إغلاق المحاولة + تصحيح)
     */
    public function submit(Request $request, Attempt $attempt)
    {
        
      $data = $request->validate([
    'answers' => ['nullable', 'array'], // ✅ كانت required وصارت nullable
    'answers.*.selected_option_id' => ['nullable', 'integer'],
    'answers.*.answer_text' => ['nullable', 'string', 'max:2000'],
]);

$this->attemptService->submitAttempt(
    attemptId: $attempt->id,
    studentId: auth()->id(),
    answers: $data['answers'] ?? [] // ✅ لو ما فيه إجابات
);

        return redirect()->route('student.results.show', $attempt)->with('success', 'تم تسليم الاختبار بنجاح.');
    }
}