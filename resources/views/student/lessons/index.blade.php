@extends('layouts.dashboard')

@section('title', __('app.lessons'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0">{{ __('app.lessons') }}</h2>
        <a class="btn btn-outline-secondary" href="{{ route('student.dashboard') }}">{{ __('app.back') }}</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.title') }}</th>
                    <th>{{ __('app.topic') }}</th>
                    <th class="text-end">{{ __('app.open') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($lessons as $l)
                    <tr>
                        <td>{{ $l->id }}</td>
                        <td>{{ $l->title }}</td>
                        <td>{{ $l->topic?->name ?? '—' }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-primary" href="{{ route('student.lessons.show', $l) }}">{{ __('app.view') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted p-4">{{ __('app.no_lessons_found') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $lessons->links() }}
        </div>
    </div>
</div>
@endsection