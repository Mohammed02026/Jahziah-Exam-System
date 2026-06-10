<?php

use App\Http\Controllers\Instructor\ExamBuilderController;
use App\Http\Controllers\Instructor\ExamController;
use App\Http\Controllers\Instructor\InstructorDashboardController;
use App\Http\Controllers\Instructor\QuestionController;
use App\Http\Controllers\Instructor\ResultsController;
use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\Instructor\LessonController;
Route::prefix('instructor')
    ->name('instructor.')
    ->middleware(['auth', (\App\Http\Middleware\RoleMiddleware::class . ':instructor')])
    ->group(function () {

        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');

        // Questions CRUD
        Route::resource('questions', QuestionController::class);

        // Exams CRUD
        Route::resource('exams', ExamController::class);

        // Exam Builder
        Route::get('/exams/{exam}/builder', [ExamBuilderController::class, 'index'])->name('exams.builder');
        Route::post('/exams/{exam}/builder/attach', [ExamBuilderController::class, 'attach'])->name('exams.builder.attach');
        Route::post('/exams/{exam}/builder/detach', [ExamBuilderController::class, 'detach'])->name('exams.builder.detach');
        Route::post('/exams/{exam}/builder/reorder', [ExamBuilderController::class, 'reorder'])->name('exams.builder.reorder');

        // Results
Route::get('/results', [ResultsController::class, 'index'])->name('results.index');
Route::get('/results/{attempt}', [ResultsController::class, 'show'])->name('results.show');

// Reports
Route::get('/reports/students', [ResultsController::class, 'studentReports'])
    ->name('reports.students');

Route::get('/reports/exams', [ResultsController::class, 'examReports'])
    ->name('reports.exams');

Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store');

Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');  

Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

        });


