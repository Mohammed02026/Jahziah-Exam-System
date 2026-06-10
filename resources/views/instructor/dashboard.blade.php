@extends('layouts.dashboard')

@section('title', __('app.instructor_dashboard_title'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="m-0">{{ __('app.instructor_dashboard_title') }}</h2>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-primary" href="{{ route('instructor.questions.index') }}">
                {{ __('app.questions') }}
            </a>

            <a class="btn btn-outline-primary" href="{{ route('instructor.exams.index') }}">
                {{ __('app.exams') }}
            </a>

            <a class="btn btn-outline-primary" href="{{ route('instructor.lessons.index') }}">
                {{ __('app.lessons') }}
            </a>

            <a class="btn btn-outline-primary" href="{{ route('instructor.results.index') }}">
                {{ __('app.results') }}
            </a>

            <a class="btn btn-outline-success" href="{{ route('instructor.reports.students') }}">
                {{ __('app.student_reports') }}
            </a>

            <a class="btn btn-outline-success" href="{{ route('instructor.reports.exams') }}">
                {{ __('app.exam_reports') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="fw-bold">{{ __('app.my_questions_count') }}</div>
                    <div class="display-6">{{ $counts['questions'] ?? 0 }}</div>

                    <a class="btn btn-sm btn-primary mt-2" href="{{ route('instructor.questions.create') }}">
                        {{ __('app.new_question') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="fw-bold">{{ __('app.my_exams_count') }}</div>
                    <div class="display-6">{{ $counts['exams'] ?? 0 }}</div>

                    <a class="btn btn-sm btn-success mt-2" href="{{ route('instructor.exams.create') }}">
                        {{ __('app.new_exam') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">{{ __('app.what_next') }}</h5>

            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-secondary" href="{{ route('instructor.questions.index') }}">
                    {{ __('app.manage_questions') }}
                </a>

                <a class="btn btn-outline-secondary" href="{{ route('instructor.exams.index') }}">
                    {{ __('app.manage_exams') }}
                </a>

                <a class="btn btn-outline-secondary" href="{{ route('instructor.lessons.index') }}">
                    {{ __('app.manage_lessons') }}
                </a>

                <a class="btn btn-outline-secondary" href="{{ route('instructor.results.index') }}">
                    {{ __('app.view_results') }}
                </a>

                <a class="btn btn-outline-success" href="{{ route('instructor.reports.students') }}">
                    {{ __('app.student_reports') }}
                </a>

                <a class="btn btn-outline-success" href="{{ route('instructor.reports.exams') }}">
                    {{ __('app.exam_reports') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection