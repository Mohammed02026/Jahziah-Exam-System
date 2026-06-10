<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('topic_id')
                ->constrained('topics')
                ->cascadeOnDelete();

            $table->longText('body');

            $table->string('type', 20)->default('mcq');         // mcq | tf | short
            $table->string('difficulty', 20)->default('easy');  // easy | medium | hard

            // Knowledge | Skills
            $table->string('learning_domain', 30)->default('knowledge');

            $table->unsignedInteger('marks')->default(1);

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index(['topic_id']);
            $table->index(['created_by']);
            $table->index(['difficulty']);
            $table->index(['type']);
            $table->index(['learning_domain']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};