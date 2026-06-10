@extends('layouts.dashboard')

@section('title', __('app.lessons'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.lessons') }}</h2>
            <div class="text-muted small">{{ __('app.lessons_manage_hint') }}</div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('instructor.dashboard') }}">{{ __('app.back') }}</a>
            <a class="btn btn-primary" href="{{ route('instructor.lessons.create') }}">{{ __('app.create_lesson') }}</a>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('instructor.lessons.index') }}" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">{{ __('app.filter') }} — {{ __('app.topic') }}</label>
                    <select name="topic_id" class="form-select">
                        <option value="">{{ __('app.all_topics') ?? '— All Topics —' }}</option>
                        @foreach($topics as $t)
                            <option value="{{ $t->id }}" @selected((string)$topicId === (string)$t->id)>
                                {{ $t->course?->name ?? '—' }} — {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-success w-100">{{ __('app.apply') }}</button>
                    <a class="btn btn-outline-danger w-100" href="{{ route('instructor.lessons.index') }}">{{ __('app.clear') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                <tr>
                    <th style="width:80px">#</th>
                    <th>{{ __('app.lesson_title') }}</th>
                    <th style="width:240px">{{ __('app.course') }} / {{ __('app.topic') }}</th>
                    <th style="width:160px">{{ __('app.created_at') }}</th>
                    <th class="text-end" style="width:220px">{{ __('app.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($lessons as $l)
                    <tr>
                        <td>{{ $l->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $l->title }}</div>
                            @if($l->content)
                                <div class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($l->content), 80) }}
                                </div>
                            @endif
                        </td>

                        <td>
                            <div class="small">
                                <div><b>{{ $l->topic?->course?->name ?? '—' }}</b></div>
                                <div class="text-muted">{{ $l->topic?->name ?? '—' }}</div>
                            </div>
                        </td>

                        <td class="small text-muted">{{ $l->created_at?->format('Y-m-d H:i') }}</td>

                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary"
                               href="{{ route('instructor.lessons.show', $l) }}">{{ __('app.view') }}</a>

                            <a class="btn btn-sm btn-outline-warning"
                               href="{{ route('instructor.lessons.edit', $l) }}">{{ __('app.edit') }}</a>

                            <form method="POST" action="{{ route('instructor.lessons.destroy', $l) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('{{ __('app.delete_lesson_confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">{{ __('app.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted p-4">{{ __('app.no_lessons') ?? 'No lessons found.' }}</td>
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