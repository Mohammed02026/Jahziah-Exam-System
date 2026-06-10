@extends('layouts.dashboard')

@section('title', __('app.edit_lesson'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.edit_lesson') }}</h2>
            <div class="text-muted small">
                {{ __('app.edit_lesson_hint') ?? 'Edit lesson details and link it to a topic.' }}
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="{{ route('instructor.lessons.index') }}">
            {{ __('app.back') }}
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">
                {{ __('app.validation_errors') ?? 'يوجد أخطاء:' }}
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('instructor.lessons.update', $lesson) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">{{ __('app.topic') }}</label>

                    <select name="topic_id" class="form-select" required>
                        <option value="">{{ __('app.choose_topic') }}</option>

                        @foreach($topics as $t)
                            <option value="{{ $t->id }}"
                                    @selected(old('topic_id', $lesson->topic_id) == $t->id)>
                                {{ $t->course?->name ?? '—' }} — {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('app.lesson_title') }}</label>

                    <input type="text"
                           name="title"
                           class="form-control lesson-input-ltr"
                           maxlength="200"
                           value="{{ old('title', $lesson->title) }}"
                           dir="ltr"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('app.lesson_content') }}</label>

                    <textarea name="content"
                              class="form-control lesson-content-ltr"
                              rows="8"
                              dir="ltr"
                              placeholder="{{ __('app.lesson_content_placeholder') ?? 'Write lesson content here...' }}">{{ old('content', $lesson->content) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('instructor.lessons.index') }}">
                        {{ __('app.cancel') ?? 'Cancel' }}
                    </a>

                    <button class="btn btn-success">
                        {{ __('app.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .lesson-input-ltr,
    .lesson-content-ltr {
        text-align: left !important;
        direction: ltr !important;
        unicode-bidi: plaintext;
    }

    .lesson-content-ltr {
        line-height: 1.8;
        font-size: 15px;
    }
</style>
@endsection