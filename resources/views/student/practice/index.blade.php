@extends('layouts.dashboard')

@section('title', __('app.practice_title'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0">{{ __('app.practice_title') }}</h2>

        <a class="btn btn-outline-secondary" href="{{ route('student.dashboard') }}">
            {{ __('app.back') === 'app.back' ? 'رجوع' : __('app.back') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(!empty($selectedTopic))
        <div class="alert alert-info">
            <strong>Practice topic:</strong>
            {{ $selectedTopic->course?->name ?? 'Course' }} – {{ $selectedTopic->name }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted mb-3">
                {{ __('app.practice_hint') }}
            </p>

            <form method="GET" action="{{ route('student.practice.session') }}" class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.topic') }}</label>

                    <select name="topic_id" class="form-select">
                        <option value="">{{ __('app.any_topic') }}</option>

                        @foreach($topics as $t)
                            <option value="{{ $t->id }}" @selected(($filters['topic_id'] ?? null) == $t->id)>
                                {{ $t->course?->name ?? 'Course' }} – {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.difficulty') }}</label>

                    <select name="difficulty" class="form-select">
                        <option value="">{{ __('app.any_level') }}</option>

                        @foreach($difficultyOptions as $val => $label)
                            <option value="{{ $val }}" @selected(($filters['difficulty'] ?? null) == $val)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary w-100">
                        {{ __('app.start_practice_session') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection