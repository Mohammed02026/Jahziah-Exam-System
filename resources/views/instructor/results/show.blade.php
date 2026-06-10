@extends('layouts.dashboard')

@section('title', __('app.result_details'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.result_details') }}</h2>
            <div class="text-muted small">
                {{ __('app.attempt') }} #{{ $attempt->id }} — {{ __('app.exams') }}: <b>{{ $attempt->exam?->title }}</b>
            </div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('instructor.results.index') }}">{{ __('app.back') }}</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">{{ __('app.student') }}</div>
                    <div class="fw-bold">{{ $attempt->student?->name }}</div>
                    <div class="text-muted small">{{ $attempt->student?->email }}</div>

                    <hr>

                    <div class="text-muted small">{{ __('app.status') }}</div>
                    <div class="fw-bold">
                        <span class="badge bg-secondary">
                            {{ $attempt->status?->value ?? $attempt->status }}
                        </span>
                    </div>

                    <div class="text-muted small mt-3">{{ __('app.score') }}</div>
                    <div class="display-6">{{ $attempt->score }}</div>

                    <div class="text-muted small mt-3">{{ __('app.started') }}</div>
                    <div class="fw-bold">{{ $attempt->started_at?->format('Y-m-d H:i') ?? '—' }}</div>

                    <div class="text-muted small mt-3">{{ __('app.submitted') }}</div>
                    <div class="fw-bold">{{ $attempt->submitted_at?->format('Y-m-d H:i') ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="fw-bold">{{ __('app.answers_details') }}</div>
                </div>
                <div class="card-body">
                    @php($items = $details['items'] ?? [])
                    @if(empty($items))
                        <div class="text-muted">{{ __('app.no_details') }}</div>
                    @else
                        <div class="accordion" id="answersAccordion">
                            @foreach($items as $idx => $it)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $idx }}">
                                        <button class="accordion-button {{ $idx ? 'collapsed' : '' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $idx }}">
                                            Q{{ $idx+1 }} — {{ __('app.marks') }}: {{ $it['marks_awarded'] }}/{{ $it['marks'] }}
                                            @if($it['is_correct'])
                                                <span class="badge bg-success ms-2">{{ __('app.correct') }}</span>
                                            @else
                                                <span class="badge bg-danger ms-2">{{ __('app.wrong') }}</span>
                                            @endif
                                        </button>
                                    </h2>

                                    <div id="collapse{{ $idx }}" class="accordion-collapse collapse {{ $idx ? '' : 'show' }}"
                                         data-bs-parent="#answersAccordion">
                                        <div class="accordion-body">
                                            <div class="mb-2">
                                                <div class="text-muted small">{{ __('app.question') }}</div>
                                                <div class="p-2 bg-light rounded">{!! nl2br(e($it['question'])) !!}</div>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <div class="text-muted small">{{ __('app.student_answer') }}</div>
                                                    <div class="p-2 border rounded">
                                                        @if(!empty($it['selected_text']))
                                                            {{ $it['selected_text'] }}
                                                        @elseif(!empty($it['answer_text']))
                                                            {!! nl2br(e($it['answer_text'])) !!}
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="text-muted small">{{ __('app.correct_answer') }}</div>
                                                    <div class="p-2 border rounded">
                                                        {{ $it['correct_text'] ?? '—' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-muted small mt-2">
                                                {{ __('app.topic') }}: {{ $it['topic'] ?? '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection