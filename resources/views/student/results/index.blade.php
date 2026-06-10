@extends('layouts.dashboard')

@section('title', __('app.my_results'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0">{{ __('app.my_results') }}</h2>
        <a class="btn btn-outline-secondary" href="{{ route('student.dashboard') }}">{{ __('app.back') }}</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card shadow-sm">
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
                @forelse($attempts as $a)
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
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted p-4">{{ __('app.no_results_yet') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $attempts->links() }}
        </div>
    </div>
</div>
@endsection