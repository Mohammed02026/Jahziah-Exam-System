<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;

class InstructorDashboardController extends Controller
{
    public function index()
    {
        $instructorId = auth()->id();

        return view('instructor.dashboard', [
            'counts' => [
                'questions' => Question::where('created_by', $instructorId)->count(),
                'exams' => Exam::where('created_by', $instructorId)->count(),
            ],
        ]);
    }
}
