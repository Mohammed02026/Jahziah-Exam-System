<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class FiftyExamsSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::query()
            ->whereIn('email', [
                'a@example.com',
                't@example.com',
            ])
            ->where('role', UserRole::Instructor->value)
            ->get();

        if ($doctors->isEmpty()) {
            return;
        }

        $courses = Course::query()
            ->orderBy('id')
            ->get();

        if ($courses->isEmpty()) {
            return;
        }

        $topicIdsByCourse = Topic::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->get()
            ->groupBy('course_id')
            ->map(fn ($rows) => $rows->pluck('id')->values()->all())
            ->toArray();

        foreach ($doctors as $doctor) {
            $questionIdsByCourse = [];

            foreach ($courses as $course) {
                $topicIds = $topicIdsByCourse[$course->id] ?? [];

                $questionIds = Question::query()
                    ->when(!empty($topicIds), fn ($query) => $query->whereIn('topic_id', $topicIds))
                    ->where('created_by', $doctor->id)
                    ->pluck('id')
                    ->toArray();

                if (empty($questionIds)) {
                    $questionIds = Question::query()
                        ->when(!empty($topicIds), fn ($query) => $query->whereIn('topic_id', $topicIds))
                        ->pluck('id')
                        ->toArray();
                }

                $questionIdsByCourse[$course->id] = $questionIds;
            }

            $courseCount = $courses->count();

            for ($i = 1; $i <= 50; $i++) {
                $course = $courses[($i - 1) % $courseCount];

                $title = "{$course->name} Exam {$i}";

                $exam = Exam::updateOrCreate(
                    [
                        'title' => $title,
                        'created_by' => $doctor->id,
                    ],
                    [
                        'course_id' => (int) $course->id,
                        'topic_id' => null,
                        'duration_minutes' => rand(10, 60),
                        'status' => (rand(1, 100) <= 60) ? 'published' : 'draft',
                        'total_marks' => 0,
                    ]
                );

                $questionIds = $questionIdsByCourse[$course->id] ?? [];

                if (count($questionIds) === 0) {
                    continue;
                }

                $pickCount = min(rand(8, 20), count($questionIds));

                shuffle($questionIds);

                $pickedQuestions = array_slice($questionIds, 0, $pickCount);

                $sync = [];
                $order = 1;
                $totalMarks = 0;

                foreach ($pickedQuestions as $questionId) {
                    $marks = rand(1, 6);

                    $sync[$questionId] = [
                        'order' => $order++,
                        'marks' => $marks,
                    ];

                    $totalMarks += $marks;
                }

                $exam->questions()->sync($sync);

                $exam->total_marks = $totalMarks;
                $exam->save();
            }
        }
    }
}