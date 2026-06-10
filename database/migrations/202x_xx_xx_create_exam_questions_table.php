<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();

            $table->unsignedInteger('order')->default(1);
            $table->unsignedInteger('marks')->nullable(); // إن أردت override لعلامة السؤال داخل الامتحان

            $table->timestamps();

            $table->unique(['exam_id', 'question_id']);
            $table->index(['exam_id']);
            $table->index(['question_id']);
            $table->index(['order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
