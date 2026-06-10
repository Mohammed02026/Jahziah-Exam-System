@extends('layouts.dashboard')

@section('title', __('app.lesson_details'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ $lesson->title }}</h2>
            <div class="text-muted small">
                {{ __('app.course') }}: <b>{{ $lesson->topic?->course?->name ?? '—' }}</b> |
                {{ __('app.topic') }}: <b>{{ $lesson->topic?->name ?? '—' }}</b> |
                {{ __('app.created_at') }}: <b>{{ $lesson->created_at?->format('Y-m-d H:i') }}</b>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('instructor.lessons.index') }}">
                {{ __('app.back') }}
            </a>

            <a class="btn btn-outline-warning" href="{{ route('instructor.lessons.edit', $lesson) }}">
                {{ __('app.edit') }}
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($lesson->content)
                <div class="p-3 bg-light rounded lesson-content" dir="ltr">
                    {!! nl2br(e($lesson->content)) !!}
                </div>
            @else
                <div class="text-muted">{{ __('app.no_lesson_content') }}</div>
            @endif
        </div>

        <div class="card-footer d-flex justify-content-end">
            <form method="POST"
                  action="{{ route('instructor.lessons.destroy', $lesson) }}"
                  onsubmit="return confirm('{{ __('app.delete_lesson_confirm') }}');">
                @csrf
                @method('DELETE')

                <button class="btn btn-outline-danger">
                    {{ __('app.delete') }}
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .lesson-content {
        text-align: left !important;
        direction: ltr !important;
        unicode-bidi: plaintext;
        line-height: 1.8;
        font-size: 15px;
        white-space: normal;
    }

    .lesson-content p,
    .lesson-content div,
    .lesson-content span,
    .lesson-content li,
    .lesson-content h1,
    .lesson-content h2,
    .lesson-content h3,
    .lesson-content h4,
    .lesson-content h5,
    .lesson-content h6 {
        text-align: left !important;
        direction: ltr !important;
    }
</style>
@endsection