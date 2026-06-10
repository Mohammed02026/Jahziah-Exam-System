<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attempt_id')->constrained('attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();

            $table->foreignId('selected_option_id')
                ->nullable()
                ->constrained('question_options')
                ->nullOnDelete();

            $table->longText('answer_text')->nullable();

            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('marks_awarded')->default(0);

            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
            $table->index(['attempt_id']);
            $table->index(['question_id']);
            $table->index(['is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
