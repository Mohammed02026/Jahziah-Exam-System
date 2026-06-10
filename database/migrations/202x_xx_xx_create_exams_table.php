<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            $table->string('title', 200);

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->foreignId('topic_id')
                ->nullable()
                ->constrained('topics')
                ->nullOnDelete();

            $table->unsignedInteger('duration_minutes')->default(30);

            $table->string('status', 20)->default('draft'); // draft | published

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('total_marks')->default(0);

            $table->timestamps();

            $table->index(['course_id']);
            $table->index(['topic_id']);
            $table->index(['created_by']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};