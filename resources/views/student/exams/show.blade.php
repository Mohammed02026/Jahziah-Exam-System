@extends('layouts.dashboard')

@section('title', __('app.exam'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ $exam->title }}</h2>
            <div class="text-muted small">
                {{ __('app.course') }}: <b>{{ $exam->course?->name ?? '—' }}</b> |
                {{ __('app.duration') }}: <b>{{ $exam->duration_minutes }}</b> {{ __('app.minutes') }} |
                {{ __('app.total_marks') }}: <b>{{ $exam->total_marks ?? 0 }}</b>
            </div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('student.exams.index') }}">{{ __('app.back') }}</a>
    </div>

    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-2">{{ __('app.exam_details_card') }}</h5>
                    <ul class="mb-0">
                        <li>{{ __('app.exam_questions_count') }}: <b>{{ $exam->questions->count() }}</b></li>
                        <li>{{ __('app.exam_duration_label') }}: <b>{{ $exam->duration_minutes }}</b> {{ __('app.minutes') }}</li>
                        <li>{{ __('app.stable_connection_hint') }}</li>
                    </ul>

                    <hr>

                    @if($exam->questions->count() === 0)
                        <div class="alert alert-warning">{{ __('app.no_exam_questions_student') }}</div>
                    @else
                        <form method="POST" action="{{ route('student.exams.start', $exam) }}">
                            @csrf
                            <button class="btn btn-success">{{ __('app.start_exam') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-bold mb-2">{{ __('app.exam_instructions') }}</div>
                    <ul class="small text-muted mb-0">
                        <li>{{ __('app.read_question_hint') ?? 'Read the question carefully before answering.' }}</li>
                        <li>{{ __('app.dont_close_page_hint') ?? "Don't close the page while solving." }}</li>
                        <li>{{ __('app.submit_at_end_hint') ?? 'When finished, click Submit.' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection