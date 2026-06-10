@extends('layouts.dashboard')

@section('title', __('app.student_dashboard'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.student_dashboard') }}</h2>
            <div class="text-muted small">{{ __('app.welcome', ['name' => auth()->user()->name]) }}</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('student.lessons.index') }}">{{ __('app.lessons') }}</a>
            <a class="btn btn-outline-primary" href="{{ route('student.practice.index') }}">{{ __('app.practice') }}</a>
            <a class="btn btn-outline-primary" href="{{ route('student.exams.index') }}">{{ __('app.exams') }}</a>
            <a class="btn btn-outline-primary" href="{{ route('student.results.index') }}">{{ __('app.results') }}</a>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-bold">{{ __('app.available_exams') }}</div>
                    <div class="display-6">{{ $counts['available_exams'] ?? 0 }}</div>
                    <a class="btn btn-sm btn-primary mt-2" href="{{ route('student.exams.index') }}">{{ __('app.start_exam') }}</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-bold">{{ __('app.my_attempts') }}</div>
                    <div class="display-6">{{ $counts['attempts'] ?? 0 }}</div>
                    <a class="btn btn-sm btn-outline-secondary mt-2" href="{{ route('student.results.index') }}">{{ __('app.view_results') }}</a>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-2">{{ __('app.recent_results') }}</h5>

            @php($recent = $recentAttempts ?? collect())
            @if($recent->isEmpty())
                <div class="text-muted">{{ __('app.no_results_yet') }}</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('app.exam') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.score') }}</th>
                            <th>{{ __('app.submitted') }}</th>
                            <th class="text-end">{{ __('app.details') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recent as $a)
                            <tr>
                                <td>{{ $a->id }}</td>
                                <td>{{ $a->exam?->title }}</td>
                                <td><span class="badge bg-secondary">{{ $a->status?->value ?? $a->status }}</span></td>
                                <td class="fw-bold">{{ $a->score }}</td>
                                <td>{{ $a->submitted_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('student.results.show', $a) }}">{{ __('app.view') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection