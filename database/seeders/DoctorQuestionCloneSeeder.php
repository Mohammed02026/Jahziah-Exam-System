<?php

namespace Database\Seeders;

use App\Enums\QuestionType;
use App\Enums\UserRole;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorQuestionCloneSeeder extends Seeder
{
    public function run(): void
    {
        /*
            الهدف:
            1. جعل قسم الأسئلة موجود للدكتورين:
               a@example.com
               t@example.com

            2. نسخ بيانات السؤال كاملة ومنها:
               learning_domain = knowledge / skills

            3. إضافة أسئلة تدريب احتياطية لكل موضوع ولكل صعوبة:
               easy / medium / hard
        */

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

        /*
            نبحث عن أول مستخدم لديه أسئلة في جدول questions
            من خلال عمود created_by.
        */
        $sourceDoctorId = Question::query()
            ->whereNotNull('created_by')
            ->orderBy('created_by')
            ->value('created_by');

        if ($sourceDoctorId) {
            $sourceQuestions = Question::with('options')
                ->where('created_by', $sourceDoctorId)
                ->get();

            foreach ($doctors as $doctor) {
                foreach ($sourceQuestions as $sourceQuestion) {
                    $question = Question::updateOrCreate(
                        [
                            'topic_id' => $sourceQuestion->topic_id,
                            'body' => $sourceQuestion->body,
                            'created_by' => $doctor->id,
                        ],
                        [
                            'type' => $sourceQuestion->getRawOriginal('type'),
                            'difficulty' => $sourceQuestion->getRawOriginal('difficulty'),
                            'learning_domain' => $sourceQuestion->learning_domain ?? 'knowledge',
                            'marks' => $sourceQuestion->marks,
                        ]
                    );

                    /*
                        حذف الخيارات القديمة ثم نسخ خيارات السؤال
                        حتى تكون الأسئلة عند الدكتورين كاملة.
                    */
                    $question->options()->delete();

                    foreach ($sourceQuestion->options as $option) {
                        $question->options()->create([
                            'text' => $option->text,
                            'is_correct' => $option->is_correct,
                        ]);
                    }
                }
            }
        }

        /*
            إضافة أسئلة تدريب احتياطية لكل موضوع ولكل صعوبة
            حتى لا تظهر رسالة:
            No practice questions found for the selected topic
        */
        $topics = Topic::query()
            ->with('course')
            ->orderBy('id')
            ->get();

        if ($topics->isEmpty()) {
            return;
        }

        $difficulties = [
            'easy',
            'medium',
            'hard',
        ];

        foreach ($doctors as $doctor) {
            foreach ($topics as $topic) {
                foreach ($difficulties as $difficulty) {
                    $learningDomain = $difficulty === 'hard' ? 'skills' : 'knowledge';

                    $body = $this->practiceQuestionBody(
                        topicName: $topic->name,
                        difficulty: $difficulty,
                        learningDomain: $learningDomain
                    );

                    $question = Question::updateOrCreate(
                        [
                            'topic_id' => $topic->id,
                            'body' => $body,
                            'created_by' => $doctor->id,
                        ],
                        [
                            'type' => QuestionType::MCQ->value,
                            'difficulty' => $difficulty,
                            'learning_domain' => $learningDomain,
                            'marks' => 1,
                        ]
                    );

                    $question->options()->delete();

                    $question->options()->createMany([
                        [
                            'text' => "Correct concept related to {$topic->name}",
                            'is_correct' => true,
                        ],
                        [
                            'text' => "Unrelated concept",
                            'is_correct' => false,
                        ],
                        [
                            'text' => "Incorrect data structure behavior",
                            'is_correct' => false,
                        ],
                        [
                            'text' => "Random programming term",
                            'is_correct' => false,
                        ],
                    ]);
                }
            }
        }
    }

    private function practiceQuestionBody(string $topicName, string $difficulty, string $learningDomain): string
    {
        $difficultyLabel = ucfirst($difficulty);
        $domainLabel = ucfirst($learningDomain);

        if ($learningDomain === 'skills') {
            return "Practice {$domainLabel} Question ({$difficultyLabel}) about {$topicName}: Which option best applies {$topicName} in a practical problem?";
        }

        return "Practice {$domainLabel} Question ({$difficultyLabel}) about {$topicName}: What is the main concept of {$topicName}?";
    }
}