<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Topic;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $topicId = $request->query('topic_id');

        $topics = Topic::with('course')->orderBy('name')->get();

        $lessons = Lesson::query()
            ->with('topic.course')
            ->when($topicId, fn($q) => $q->where('topic_id', (int)$topicId))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('instructor.lessons.index', compact('lessons', 'topics', 'topicId'));
    }

    public function create()
    {
        $topics = Topic::with('course')->orderBy('name')->get();
        return view('instructor.lessons.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required','integer','exists:topics,id'],
            'title' => ['required','string','max:200'],
            'content' => ['nullable','string'],
        ]);

        Lesson::create($data);

        return redirect()->route('instructor.lessons.index')
            ->with('success', 'تم إضافة الدرس بنجاح.');
    }

    public function edit(Lesson $lesson)
    {
        $topics = Topic::with('course')->orderBy('name')->get();
        return view('instructor.lessons.edit', compact('lesson','topics'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'topic_id' => ['required','integer','exists:topics,id'],
            'title' => ['required','string','max:200'],
            'content' => ['nullable','string'],
        ]);

        $lesson->update($data);

        return redirect()->route('instructor.lessons.index')
            ->with('success', 'تم تحديث الدرس بنجاح.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return back()->with('success', 'تم حذف الدرس.');
    }

    // لو ما تستخدم show للمدرس ممكن تتركه أو تحذفه من routes
    public function show(Lesson $lesson)
    {
        $lesson->load('topic.course');
        return view('instructor.lessons.show', compact('lesson'));
    }
}