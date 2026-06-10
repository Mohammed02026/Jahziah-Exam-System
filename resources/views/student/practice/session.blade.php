@extends('layouts.dashboard')

@section('title', __('app.practice_session'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.practice_session') }}</h2>
            <div class="text-muted small">{{ __('app.practice_session_hint') }}</div>
        </div>
        <a class="btn btn-outline-secondary"
           href="{{ route('student.practice.index', ['topic_id' => $topicId, 'difficulty' => $difficulty]) }}">
            {{ __('app.back') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(!$question)
        <div class="alert alert-warning">{{ __('app.no_matching_question') }}</div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-2">
                    {{ __('app.topic') }}: <b>{{ $question->topic?->name ?? '—' }}</b> |
                    {{ __('app.difficulty') }}: <b>{{ $question->difficulty->value }}</b> |
                    {{ __('app.type') }}:
                    <b>
                        @if($question->type === \App\Enums\QuestionType::MCQ)
                            Multiple Choice
                        @elseif($question->type === \App\Enums\QuestionType::TrueFalse)
                            True / False
                        @else
                            {{ $question->type->value }}
                        @endif
                    </b>
                </div>

                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($question->body)) !!}
                </div>

                <form class="mt-3" method="POST" action="{{ route('student.practice.check') }}">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question->id }}">

                    @foreach($question->options as $option)
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="selected_option_id"
                                   value="{{ $option->id }}"
                                   id="opt{{ $option->id }}"
                                   required>
                            <label class="form-check-label" for="opt{{ $option->id }}">
                                {{ $option->text }}
                            </label>
                        </div>
                    @endforeach

                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-primary">
                            {{ __('app.check_answer') }}
                        </button>

                        <a class="btn btn-outline-secondary"
                           href="{{ route('student.practice.session', [
                                'topic_id' => $topicId,
                                'difficulty' => $difficulty,
                                'question_id' => $question->id,
                           ]) }}">
                            {{ __('app.new_practice_question') }}
                        </a>
                    </div>
                </form>

                @php($feedback = session('practice_result'))
                @if($feedback)
                    <hr>
                    <div class="alert {{ ($feedback['is_correct'] ?? false) ? 'alert-success' : 'alert-danger' }}">
                        <div class="fw-bold">
                            {{ ($feedback['is_correct'] ?? false) ? __('app.practice_correct') : __('app.practice_wrong') }}
                        </div>

                        @if(!empty($feedback['correct_text']))
                            <div class="small mt-1">
                                {{ __('app.correct_answer_label') }}: <b>{{ $feedback['correct_text'] }}</b>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection