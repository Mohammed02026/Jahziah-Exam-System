<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->string('title', 200);
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->index(['topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
