@extends('layouts.dashboard')

@section('title', __('app.question_details'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.question_details') }}</h2>
            <div class="text-muted small">#{{ $question->id }}</div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('instructor.questions.index') }}">
                {{ __('app.back') === 'app.back' ? 'رجوع' : __('app.back') }}
            </a>

            <a class="btn btn-outline-primary" href="{{ route('instructor.questions.edit', $question) }}">
                {{ __('app.edit') }}
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-2 text-muted small">
                {{ __('app.topic') }}:
                <b>
                    @if($question->topic)
                        {{ $question->topic->course?->name ?? 'Course' }} – {{ $question->topic->name }}
                    @else
                        —
                    @endif
                </b>
                |

                {{ __('app.type') }}:
                <b>
                    @php
                        $questionType = is_object($question->type) ? $question->type->value : $question->type;
                    @endphp

                    @if($questionType === 'mcq')
                        Multiple Choice
                    @elseif($questionType === 'tf')
                        True / False
                    @else
                        {{ $questionType }}
                    @endif
                </b>
                |

                {{ __('app.difficulty') }}:
                <b>
                    {{ is_object($question->difficulty) ? $question->difficulty->value : $question->difficulty }}
                </b>
                |

                {{ __('app.learning_domain') === 'app.learning_domain' ? 'Learning Domain' : __('app.learning_domain') }}:
                <b>
                    @php
                        $learningDomain = $question->learning_domain ?? 'knowledge';
                    @endphp

                    @if($learningDomain === 'knowledge')
                        Knowledge
                    @elseif($learningDomain === 'skills')
                        Skills
                    @else
                        {{ ucfirst($learningDomain) }}
                    @endif
                </b>
                |

                {{ __('app.marks') }}:
                <b>{{ $question->marks }}</b>
            </div>

            <div class="p-3 bg-light rounded">
                {!! nl2br(e($question->body)) !!}
            </div>

            <hr>

            <h6 class="mb-2">{{ __('app.options') }}</h6>

            @if(($question->options?->count() ?? 0) === 0)
                <div class="text-muted">{{ __('app.no_options') }}</div>
            @else
                <ul class="list-group">
                    @foreach($question->options as $option)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $option->text }}</span>

                            @if($option->is_correct)
                                <span class="badge bg-success">{{ __('app.correct') }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <hr>

            <form method="POST"
                  action="{{ route('instructor.questions.destroy', $question) }}"
                  onsubmit="return confirm('{{ __('app.delete_question_confirm') }}');">
                @csrf
                @method('DELETE')

                <button class="btn btn-outline-danger">
                    {{ __('app.delete_question') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection