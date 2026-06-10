<?php

use App\Http\Controllers\Student\AttemptController;
use App\Http\Controllers\Student\ExamController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\PracticeController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\Student\StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', (\App\Http\Middleware\RoleMiddleware::class . ':student')])
    ->group(function () {

        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        // Lessons
        Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
        Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');

        // Practice
        Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
        Route::get('/practice/session', [PracticeController::class, 'session'])->name('practice.session');
        Route::post('/practice/check', [PracticeController::class, 'check'])->name('practice.check');

        // Exams
        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
        Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
        Route::post('/exams/{exam}/start', [ExamController::class, 'start'])->name('exams.start');
        Route::get('/exams/{exam}/take', [ExamController::class, 'take'])->name('exams.take');

        // Submit attempt
        Route::post('/attempts/{attempt}/submit', [AttemptController::class, 'submit'])->name('attempts.submit');

        // Results
        Route::get('/results', [ResultController::class, 'index'])->name('results.index');
        Route::get('/results/{attempt}', [ResultController::class, 'show'])->name('results.show');
    });


