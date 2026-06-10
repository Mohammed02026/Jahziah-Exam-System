@extends('layouts.dashboard')

@section('title', __('app.result'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.result_details_student') }}</h2>
            <div class="text-muted small">
                {{ __('app.attempt') }} #{{ $attempt->id }} — {{ __('app.exam') }}: <b>{{ $attempt->exam?->title }}</b>
            </div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('student.results.index') }}">{{ __('app.back') }}</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">{{ __('app.status') }}</div>
                    <div class="fw-bold"><span class="badge bg-secondary">{{ $attempt->status?->value ?? $attempt->status }}</span></div>

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
                        <div class="accordion" id="studentAnswersAccordion">
                            @foreach($items as $idx => $it)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="sHeading{{ $idx }}">
                                        <button class="accordion-button {{ $idx ? 'collapsed' : '' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#sCollapse{{ $idx }}">
                                            Q{{ $idx+1 }} — {{ __('app.marks') }}: {{ $it['marks_awarded'] }}/{{ $it['marks'] }}
                                            @if($it['is_correct'])
                                                <span class="badge bg-success ms-2">{{ __('app.correct') }}</span>
                                            @else
                                                <span class="badge bg-danger ms-2">{{ __('app.wrong') }}</span>
                                            @endif
                                        </button>
                                    </h2>
                                    <div id="sCollapse{{ $idx }}" class="accordion-collapse collapse {{ $idx ? '' : 'show' }}"
                                         data-bs-parent="#studentAnswersAccordion">
                                        <div class="accordion-body">
                                            <div class="mb-2">
                                                <div class="text-muted small">{{ __('app.question') }}</div>
                                                <div class="p-2 bg-light rounded">{!! nl2br(e($it['question'])) !!}</div>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <div class="text-muted small">{{ __('app.your_answer_label') }}</div>
                                                    <div class="p-2 border rounded">
                                                        @if($it['selected_text'])
                                                            {{ $it['selected_text'] }}
                                                        @elseif($it['answer_text'])
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