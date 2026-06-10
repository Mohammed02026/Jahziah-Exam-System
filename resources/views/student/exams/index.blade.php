@extends('layouts.dashboard')

@section('title', __('app.available_exams'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0">{{ __('app.available_exams') }}</h2>
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
                    <th>{{ __('app.title') }}</th>
                    <th>{{ __('app.course') }}</th>
                    <th>{{ __('app.duration') }}</th>
                    <th>{{ __('app.total_marks') }}</th>
                    <th class="text-end">{{ __('app.exam_action') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($exams as $e)
                    <tr>
                        <td>{{ $e->id }}</td>
                        <td>{{ $e->title }}</td>
                        <td>{{ $e->course?->name ?? '—' }}</td>
                        <td>{{ $e->duration_minutes }} {{ __('app.minutes') }}</td>
                        <td class="fw-bold">{{ $e->total_marks ?? 0 }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-primary" href="{{ route('student.exams.show', $e) }}">{{ __('app.exam_open') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted p-4">{{ __('app.no_exams') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-center" dir="ltr">
            {{ $exams->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection