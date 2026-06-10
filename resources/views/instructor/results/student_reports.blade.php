@extends('layouts.dashboard')

@section('title', __('app.student_reports'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ __('app.student_reports') }}</h2>
            <div class="text-muted small">
                {{ __('app.student_reports_description') }}
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="{{ route('instructor.dashboard') }}">
            {{ __('app.back') }}
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('instructor.reports.students') }}" class="row g-2">
                <div class="col-md-10">
                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ $search }}"
                           placeholder="{{ __('app.search_student_placeholder') }}">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        {{ __('app.search') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(!$selectedStudent)
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                {{ __('app.students') }}
            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('app.student_name') }}</th>
                            <th>{{ __('app.email') }}</th>
                            <th class="text-end">{{ __('app.view_report') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td class="fw-bold">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="{{ route('instructor.reports.students', ['student_id' => $student->id, 'search' => $search]) }}">
                                        {{ __('app.view_student_report') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted p-4">
                                    {{ __('app.no_results') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                {{ $students->links() }}
            </div>
        </div>
    @endif

    @if($selectedStudent)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white fw-bold">
                {{ __('app.student_information') }}
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.student_name') }}</div>
                        <div class="fw-bold">{{ $selectedStudent->name }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.email') }}</div>
                        <div class="fw-bold">{{ $selectedStudent->email }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.role') }}</div>
                        <div class="fw-bold">{{ $selectedStudent->role?->value ?? $selectedStudent->role }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($summary)
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.total_exams') }}</div>
                            <div class="display-6">{{ $summary['total_attempts'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.passed_exams') }}</div>
                            <div class="display-6 text-success">{{ $summary['passed'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.failed_exams') }}</div>
                            <div class="display-6 text-danger">{{ $summary['failed'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.student_average') }}</div>
                            <div class="h3">{{ $summary['average'] }}%</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.highest_score') }}</div>
                            <div class="h3">{{ $summary['highest'] }}%</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.lowest_score') }}</div>
                            <div class="h3">{{ $summary['lowest'] }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                {{ __('app.student_exams') }}
            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('app.exam') }}</th>
                            <th>{{ __('app.course') }}</th>
                            <th>{{ __('app.topic') }}</th>
                            <th>{{ __('app.score') }}</th>
                            <th>{{ __('app.percentage') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.submitted_at') }}</th>
                            <th class="text-end">{{ __('app.view') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($attempts as $attempt)
                            @php
                                $totalMarks = (int)($attempt->exam?->total_marks ?? 0);
                                $percentage = $totalMarks > 0 ? round(($attempt->score / $totalMarks) * 100, 2) : 0;
                                $status = $percentage >= 60 ? __('app.passed') : __('app.failed');
                                $statusClass = $percentage >= 60 ? 'success' : 'danger';

                                $topics = $attempt->exam?->questions?->pluck('topic.name')->filter()->unique()->values() ?? collect();
                            @endphp

                            <tr>
                                <td>{{ $attempt->id }}</td>

                                <td>{{ $attempt->exam?->title }}</td>

                                <td>{{ $attempt->exam?->course?->name ?? '—' }}</td>

                                <td class="topic-cell">
                                    @if($topics->count() > 0)
                                        <span class="topic-main">{{ $topics->first() }}</span>

                                        @if($topics->count() > 1)
                                            <span class="topics-tooltip-wrapper">
                                                <span class="badge bg-secondary topics-badge">
                                                    + {{ $topics->count() - 1 }} {{ __('app.topics') }}
                                                </span>

                                                <div class="topics-tooltip-box">
                                                    <div class="fw-bold mb-2">{{ __('app.related_topics') }}</div>

                                                    <div class="topics-list">
                                                        @foreach($topics as $topic)
                                                            <span class="topic-item">
                                                                {{ $topic }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </span>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="fw-bold">{{ $attempt->score }} / {{ $totalMarks }}</td>

                                <td>{{ $percentage }}%</td>

                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ $status }}</span>
                                </td>

                                <td>{{ $attempt->submitted_at?->format('Y-m-d H:i') ?? '—' }}</td>

                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="{{ route('instructor.results.show', $attempt) }}">
                                        {{ __('app.details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted p-4">
                                    {{ __('app.no_student_exams') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    .topic-cell {
        min-width: 260px;
        position: relative;
    }

    .topic-main {
        white-space: nowrap;
    }

    .topics-tooltip-wrapper {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .topics-badge {
        margin-inline-start: 6px;
        font-size: 12px;
        padding: 6px 10px;
        vertical-align: middle;
    }

    .topics-tooltip-box {
        display: none;
        position: absolute;
        bottom: 135%;
        right: 50%;
        transform: translateX(50%);
        width: 520px;
        max-width: 70vw;
        background: #ffffff;
        color: #212529;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 12px 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        z-index: 9999;
        text-align: start;
        font-size: 13px;
        line-height: 1.8;
        white-space: normal;
    }

    .topics-tooltip-wrapper:hover .topics-tooltip-box {
        display: block;
    }

    .topics-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .topic-item {
        display: inline-flex;
        align-items: center;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 12px;
        white-space: nowrap;
    }

    .topics-tooltip-box::after {
        content: "";
        position: absolute;
        top: 100%;
        right: 50%;
        transform: translateX(50%);
        border-width: 7px;
        border-style: solid;
        border-color: #ffffff transparent transparent transparent;
    }
</style>
@endsection