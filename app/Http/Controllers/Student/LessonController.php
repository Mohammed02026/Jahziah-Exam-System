<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Topic;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $topicId = $request->query('topic_id');

        $topics = Topic::orderBy('name')->get();

        $lessons = Lesson::query()
            ->with('topic')
            ->when(!empty($topicId), function ($query) use ($topicId) {
                $query->where('topic_id', (int) $topicId);
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('student.lessons.index', compact('lessons', 'topics', 'topicId'));
    }

    public function show(Lesson $lesson)
    {
        $lesson->load('topic');

        return view('student.lessons.show', compact('lesson'));
    }
}