@extends('layouts.dashboard')

@section('title', __('app.results'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0">{{ __('app.students_results') }}</h2>
        <a class="btn btn-outline-secondary" href="{{ route('instructor.dashboard') }}">{{ __('app.back') }}</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('instructor.results.index') }}" class="row g-2">
                <div class="col-md-10">
                    <select name="exam_id" class="form-select">
                        <option value="">{{ __('app.exams') }}</option>
                        @foreach($exams as $e)
                            <option value="{{ $e->id }}" @selected((string)$examId === (string)$e->id)>{{ $e->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">{{ __('app.apply') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('app.student') }}</th>
                        <th>{{ __('app.exams') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th>{{ __('app.score') }}</th>
                        <th>{{ __('app.submitted') }}</th>
                        <th class="text-end">{{ __('app.view') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>
                                {{ $r->student?->name }}
                                <div class="text-muted small">{{ $r->student?->email }}</div>
                            </td>
                            <td>{{ $r->exam?->title }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $r->status?->value ?? $r->status }}</span>
                            </td>
                            <td class="fw-bold">{{ $r->score }}</td>
                            <td>{{ $r->submitted_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('instructor.results.show', $r) }}">
                                    {{ __('app.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">{{ __('app.no_results') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $results->links() }}
        </div>
    </div>
</div>
@endsection