@extends('layouts.dashboard')

@section('title', __('app.my_questions'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0">{{ __('app.my_questions') }}</h2>

        <div class="d-flex gap-2">
            @php
                $backUrl = \Illuminate\Support\Facades\Route::has('instructor.dashboard')
                    ? route('instructor.dashboard')
                    : url('/instructor');
            @endphp

            <a class="btn btn-outline-secondary" href="{{ $backUrl }}">
                {{ __('app.back') === 'app.back' ? 'رجوع' : __('app.back') }}
            </a>

            <a class="btn btn-success" href="{{ route('instructor.questions.create') }}">
                + {{ __('app.new_question') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2" method="GET" action="{{ route('instructor.questions.index') }}">
                <div class="col-md-3">
                    <input type="text"
                           name="q"
                           value="{{ $filters['q'] ?? '' }}"
                           class="form-control"
                           placeholder="{{ __('app.search_question_placeholder') }}">
                </div>

                <div class="col-md-3">
                    <select name="topic_id" class="form-select">
                        <option value="">{{ __('app.topic') }}</option>

                        @foreach($topics as $t)
                            <option value="{{ $t->id }}" @selected(($filters['topic_id'] ?? '') == $t->id)>
                                {{ $t->course?->name ?? 'Course' }} – {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="difficulty" class="form-select">
                        <option value="">{{ __('app.difficulty') }}</option>

                        @foreach($difficultyOptions as $val => $label)
                            <option value="{{ $val }}" @selected(($filters['difficulty'] ?? '') == $val)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="learning_domain" class="form-select">
                        <option value="">
                            {{ __('app.learning_domain') === 'app.learning_domain' ? 'Learning Domain' : __('app.learning_domain') }}
                        </option>

                        <option value="knowledge" @selected(($filters['learning_domain'] ?? '') === 'knowledge')>
                            Knowledge
                        </option>

                        <option value="skills" @selected(($filters['learning_domain'] ?? '') === 'skills')>
                            Skills
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        {{ __('app.filter') }}
                    </button>
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
                        <th>{{ __('app.question') }}</th>
                        <th>{{ __('app.topic') }}</th>
                        <th>{{ __('app.type') }}</th>
                        <th>{{ __('app.difficulty') }}</th>
                        <th>{{ __('app.learning_domain') === 'app.learning_domain' ? 'Learning Domain' : __('app.learning_domain') }}</th>
                        <th>{{ __('app.marks') }}</th>
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($questions as $q)
                        @php
                            $learningDomain = $q->learning_domain ?? 'knowledge';
                        @endphp

                        <tr>
                            <td>{{ $q->id }}</td>

                            <td>{{ \Illuminate\Support\Str::limit($q->body, 80) }}</td>

                            <td>
                                @if($q->topic)
                                    {{ $q->topic->course?->name ?? 'Course' }} – {{ $q->topic->name }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ is_object($q->type) ? $q->type->value : $q->type }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ is_object($q->difficulty) ? $q->difficulty->value : $q->difficulty }}
                                </span>
                            </td>

                            <td>
                                @if($learningDomain === 'knowledge')
                                    <span class="badge bg-dark">Knowledge</span>
                                @elseif($learningDomain === 'skills')
                                    <span class="badge bg-primary">Skills</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($learningDomain) }}</span>
                                @endif
                            </td>

                            <td class="fw-bold">{{ $q->marks }}</td>

                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('instructor.questions.show', $q) }}">
                                    {{ __('app.view') }}
                                </a>

                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('instructor.questions.edit', $q) }}">
                                    {{ __('app.edit') }}
                                </a>

                                <form class="d-inline"
                                      method="POST"
                                      action="{{ route('instructor.questions.destroy', $q) }}"
                                      onsubmit="return confirm('{{ __('app.delete_question_confirm') }}');">
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
                                {{ __('app.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-center">
            {{ $questions->links() }}
        </div>
    </div>
</div>
@endsection