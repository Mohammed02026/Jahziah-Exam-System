@extends('layouts.dashboard')

@section('title', __('app.my_exams'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0">{{ __('app.my_exams') }}</h2>

        <div class="d-flex gap-2">
            @php
                $backUrl = \Illuminate\Support\Facades\Route::has('instructor.dashboard')
                    ? route('instructor.dashboard')
                    : url('/instructor');
            @endphp

            <a class="btn btn-outline-secondary" href="{{ $backUrl }}">
                {{ __('app.back') === 'app.back' ? 'رجوع' : __('app.back') }}
            </a>

            <a class="btn btn-success" href="{{ route('instructor.exams.create') }}">
                {{ __('app.new_exam') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('app.exam_title') }}</th>
                        <th>{{ __('app.course') }}</th>
                        <th>{{ __('app.topic') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th>{{ __('app.duration') }}</th>
                        <th>{{ __('app.total_marks') }}</th>
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($exams as $e)
                        <tr>
                            <td>{{ $e->id }}</td>

                            <td>{{ $e->title }}</td>

                            <td>
                                {{ $e->course?->name ?? '—' }}
                            </td>

                            <td>
                                @if($e->topic)
                                    {{ $e->topic->course?->name ?? $e->course?->name ?? 'Course' }} – {{ $e->topic->name }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $e->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $e->status }}
                                </span>
                            </td>

                            <td>
                                {{ $e->duration_minutes }}
                                {{ __('app.minutes') === 'app.minutes' ? 'min' : __('app.minutes') }}
                            </td>

                            <td>{{ $e->total_marks ?? 0 }}</td>

                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('instructor.exams.show', $e) }}">
                                    {{ __('app.view') }}
                                </a>

                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('instructor.exams.edit', $e) }}">
                                    {{ __('app.edit') }}
                                </a>

                                <a class="btn btn-sm btn-outline-success" href="{{ route('instructor.exams.builder', $e) }}">
                                    {{ __('app.exam_builder') }}
                                </a>

                                <form class="d-inline"
                                      method="POST"
                                      action="{{ route('instructor.exams.destroy', $e) }}"
                                      onsubmit="return confirm('{{ __('app.delete_exam_confirm') }}');">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger">
                                        {{ __('app.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted p-4">
                                {{ __('app.no_exams') === 'app.no_exams' ? 'No exams found.' : __('app.no_exams') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-center">
            {{ $exams->links() }}
        </div>
    </div>
</div>
@endsection