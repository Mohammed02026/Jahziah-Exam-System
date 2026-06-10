@extends('layouts.dashboard')

@section('title', __('app.lesson'))

@push('styles')
<style>
    .lesson-title{
        display: inline-block;
        font-size: 2rem;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: .3px;
        line-height: 1.3;
        padding-bottom: .35rem;
        border-bottom: 4px solid #3b82f6;
    }

    .lesson-content-box{
        direction: ltr;
        text-align: left;
        unicode-bidi: plaintext;
        background: #f8fafc;
        border: 1px solid rgba(148,163,184,.28);
        border-radius: 18px;
        padding: 1.4rem;
        font-size: 1.08rem;
        line-height: 2;
        color: #0f172a;
    }

    .lesson-text{
        margin-bottom: .9rem;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="lesson-title m-0">{{ $lesson->title }}</h2>

            <div class="text-muted small mt-2">
                {{ __('app.topic') }}:
                <b>{{ $lesson->topic?->name ?? '—' }}</b>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary"
               href="{{ route('student.practice.index', ['topic_id' => $lesson->topic_id]) }}">
                Practice this topic
            </a>

            <a class="btn btn-outline-secondary" href="{{ route('student.lessons.index') }}">
                {{ __('app.back') }}
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if(!$lesson->content)
                <div class="text-muted">
                    {{ __('app.no_lesson_content') }}
                </div>
            @else
                <div class="lesson-content-box" dir="ltr">
                    @php
                        $lines = preg_split('/\r\n|\r|\n/', $lesson->content);
                    @endphp

                    @foreach($lines as $line)
                        @if(trim($line) === '')
                            <br>
                        @else
                            <p class="lesson-text">{{ $line }}</p>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection