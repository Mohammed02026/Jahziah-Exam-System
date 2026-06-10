@extends('layouts.dashboard')

@section('title', __('app.exam_details'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.exam_details') }}</h2>
            <div class="text-muted small">#{{ $exam->id }} — {{ $exam->title }}</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('instructor.exams.index') }}">{{ __('app.back') }}</a>
            <a class="btn btn-outline-primary" href="{{ route('instructor.exams.edit', $exam) }}">{{ __('app.edit') }}</a>
            <a class="btn btn-success" href="{{ route('instructor.exams.builder', $exam) }}">{{ __('app.exam_builder') }}</a>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">{{ __('app.status') }}</div>
                    <div class="fw-bold">
                        <span class="badge {{ $exam->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $exam->status }}
                        </span>
                    </div>

                    <hr>

                    <div class="text-muted small">{{ __('app.duration') }}</div>
                    <div class="fw-bold">{{ $exam->duration_minutes }} {{ __('app.minutes') ?? 'minutes' }}</div>

                    <div class="text-muted small mt-3">{{ __('app.total_marks') }}</div>
                    <div class="fw-bold">{{ $exam->total_marks ?? 0 }}</div>

                    <div class="text-muted small mt-3">{{ __('app.course') }}</div>
                    <div class="fw-bold">{{ $exam->course?->name ?? '—' }}</div>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('instructor.exams.destroy', $exam) }}"
                          onsubmit="return confirm('{{ __('app.delete_exam_confirm') }}');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100">{{ __('app.delete_exam') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="fw-bold">{{ __('app.exam_questions') }}</div>
                    <div class="text-muted small">{{ __('app.exam_questions_hint') ?? 'List of added questions (ordered).' }}</div>
                </div>
                <div class="card-body">
                    @if($exam->questions->count() === 0)
                        <div class="text-muted">{{ __('app.no_exam_questions') }}</div>
                        <a class="btn btn-success mt-2" href="{{ route('instructor.exams.builder', $exam) }}">{{ __('app.go_to_builder') }}</a>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('app.question') }}</th>
                                        <th>{{ __('app.marks') }}</th>
                                        <th>{{ __('app.order') ?? 'Order' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exam->questions as $q)
                                        <tr>
                                            <td>{{ $q->id }}</td>
                                            <td>
                                                <div class="small">{{ \Illuminate\Support\Str::limit($q->body, 150) }}</div>
                                                <div class="text-muted small">
                                                    {{ __('app.topic') }}: {{ $q->topic?->name ?? '—' }} |
                                                    {{ __('app.difficulty') }}: {{ $q->difficulty?->value ?? $q->difficulty }} |
                                                    {{ __('app.type') }}: {{ $q->type?->value ?? $q->type }}
                                                </div>
                                            </td>
                                            <td class="fw-bold">{{ $q->pivot->marks ?? $q->marks }}</td>
                                            <td>{{ $q->pivot->order }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection