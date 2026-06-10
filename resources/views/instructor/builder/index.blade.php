@extends('layouts.dashboard')

@section('title', __('app.exam_builder'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.exam_builder') }}</h2>
            <div class="text-muted small">
                {{ __('app.exams') }}: <b>{{ $exam->title }}</b> — {{ __('app.total_marks') }}: <b>{{ $vm['totalMarks'] ?? 0 }}</b>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('instructor.exams.show', $exam) }}">{{ __('app.back') }}</a>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row g-3">
        {{-- Selected Questions --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="fw-bold">{{ __('app.selected_questions') }}</div>
                    <div class="text-muted small">{{ __('app.selected_questions_hint') }}</div>
                </div>
                <div class="card-body">
                    @php($selected = $vm['selectedQuestions'] ?? collect())

                    @if($selected->isEmpty())
                        <div class="text-muted">{{ __('app.no_exam_questions') }}</div>
                    @else
                        <form method="POST" action="{{ route('instructor.exams.builder.reorder', $exam) }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:90px;">{{ __('app.order') ?? 'Order' }}</th>
                                            <th>{{ __('app.question') }}</th>
                                            <th style="width:90px;">{{ __('app.marks') }}</th>
                                            <th class="text-end" style="width:120px;">{{ __('app.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selected as $i => $q)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="orders[{{ $i }}][question_id]" value="{{ $q->id }}">
                                                    <input type="number" class="form-control" name="orders[{{ $i }}][order]" value="{{ $q->pivot->order }}" min="1" max="500">
                                                </td>
                                                <td>
                                                    <div class="fw-bold">#{{ $q->id }}</div>
                                                    <div class="small">{{ \Illuminate\Support\Str::limit($q->body, 120) }}</div>
                                                    <div class="text-muted small">
                                                        {{ __('app.topic') }}: {{ $q->topic?->name ?? '—' }} |
                                                        {{ __('app.difficulty') }}: {{ $q->difficulty?->value ?? $q->difficulty ?? '—' }}
                                                    </div>
                                                </td>
                                                <td class="fw-bold">{{ $q->pivot->marks ?? $q->marks }}</td>
                                                <td class="text-end">
                                                    <form method="POST" action="{{ route('instructor.exams.builder.detach', $exam) }}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="question_id" value="{{ $q->id }}">
                                                        <button class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('{{ __('app.remove_question_confirm') ?? 'Remove this question from the exam?' }}')">
                                                            {{ __('app.remove') }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button class="btn btn-primary">{{ __('app.save_order') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Available Questions --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="fw-bold">{{ __('app.available_questions') }}</div>
                    <div class="text-muted small">{{ __('app.available_questions_hint') }}</div>
                </div>

                <div class="card-body">
                    <form class="row g-2 mb-3" method="GET" action="{{ route('instructor.exams.builder', $exam) }}">
                        <div class="col-md-6">
                            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="{{ __('app.search') ?? 'Search...' }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="topic_id">
                                <option value="">{{ __('app.topic') }}</option>
                                @foreach($topics as $t)
                                    <option value="{{ $t->id }}" @selected(($filters['topic_id'] ?? '') == $t->id)>{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="difficulty">
                                <option value="">{{ __('app.difficulty') }}</option>
                                @foreach($difficultyOptions as $val => $label)
                                    <option value="{{ $val }}" @selected(($filters['difficulty'] ?? '') == $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-primary w-100">{{ __('app.apply') }}</button>
                        </div>
                    </form>

                    @php($available = $vm['availableQuestions'] ?? null)
                    @if(!$available || $available->count() === 0)
                        <div class="text-muted">{{ __('app.no_available_questions') ?? 'No available questions for the selected filters.' }}</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('app.question') }}</th>
                                        <th style="width:120px;">{{ __('app.marks') }}</th>
                                        <th class="text-end" style="width:120px;">{{ __('app.add') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($available as $q)
                                        <tr>
                                            <td>{{ $q->id }}</td>
                                            <td>
                                                <div class="small">{{ \Illuminate\Support\Str::limit($q->body, 120) }}</div>
                                                <div class="text-muted small">
                                                    {{ __('app.topic') }}: {{ $q->topic?->name ?? '—' }} |
                                                    {{ __('app.difficulty') }}: {{ $q->difficulty?->value ?? $q->difficulty }}
                                                </div>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('instructor.exams.builder.attach', $exam) }}" class="d-flex gap-2">
                                                    @csrf
                                                    <input type="hidden" name="question_id" value="{{ $q->id }}">
                                                    <input type="number"
                                                           name="marks"
                                                           class="form-control form-control-sm"
                                                           value="{{ $q->marks }}"
                                                           min="1"
                                                           max="100"
                                                           title="{{ __('app.override_marks') }}">
                                            </td>
                                            <td class="text-end">
                                                    <button class="btn btn-sm btn-success">{{ __('app.add') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $available->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection