@extends('layouts.dashboard')

@section('title', __('app.exam_reports'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h2 class="m-0">{{ __('app.exam_reports') }}</h2>
            <div class="text-muted small">
                {{ __('app.exam_reports_description') }}
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="{{ route('instructor.dashboard') }}">
            {{ __('app.back') }}
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('instructor.reports.exams') }}" class="row g-2">
                <div class="col-md-10">
                    <select name="exam_id" class="form-select">
                        <option value="">{{ __('app.select_exam') }}</option>

                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" @selected((string)$selectedExamId === (string)$exam->id)>
                                {{ $exam->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        {{ __('app.show_report') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedExam)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white fw-bold">
                {{ __('app.exam_information') }}
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.exam_name') }}</div>
                        <div class="fw-bold">{{ $selectedExam->title }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.course') }}</div>
                        <div class="fw-bold">{{ $selectedExam->course?->name ?? '—' }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.topic') }}</div>
                        <div class="fw-bold">{{ $selectedExam->topic?->name ?? '—' }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.exam_duration') }}</div>
                        <div class="fw-bold">{{ $selectedExam->duration_minutes }} {{ __('app.minutes') }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.total_marks') }}</div>
                        <div class="fw-bold">{{ $selectedExam->total_marks }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('app.exam_status') }}</div>
                        <div class="fw-bold">{{ $selectedExam->status }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($summary)
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.students_attempted_count') }}</div>
                            <div class="display-6">{{ $summary['total'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.passed_count') }}</div>
                            <div class="display-6 text-success">{{ $summary['passed'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.failed_count') }}</div>
                            <div class="display-6 text-danger">{{ $summary['failed'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.excellent_count') }}</div>
                            <div class="display-6 text-primary">{{ $summary['excellent'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.pass_rate') }}</div>
                            <div class="h3 text-success">{{ $summary['pass_rate'] }}%</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.fail_rate') }}</div>
                            <div class="h3 text-danger">{{ $summary['fail_rate'] }}%</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.average_score') }}</div>
                            <div class="h3">{{ $summary['average'] }}%</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ __('app.excellent_rate') }}</div>
                            <div class="h3 text-primary">{{ $summary['excellent_rate'] }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                {{ __('app.attempted_students_list') }}
            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('app.student') }}</th>
                            <th>{{ __('app.student_email') }}</th>
                            <th>{{ __('app.grade') }}</th>
                            <th>{{ __('app.percentage') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.evaluation') }}</th>
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

                                if ($percentage >= 90) {
                                    $level = __('app.excellent');
                                    $levelClass = 'primary';
                                } elseif ($percentage >= 80) {
                                    $level = __('app.very_good');
                                    $levelClass = 'info';
                                } elseif ($percentage >= 70) {
                                    $level = __('app.good');
                                    $levelClass = 'secondary';
                                } elseif ($percentage >= 60) {
                                    $level = __('app.acceptable');
                                    $levelClass = 'warning';
                                } else {
                                    $level = __('app.weak');
                                    $levelClass = 'danger';
                                }
                            @endphp

                            <tr>
                                <td>{{ $attempt->id }}</td>
                                <td class="fw-bold">{{ $attempt->student?->name }}</td>
                                <td>{{ $attempt->student?->email }}</td>
                                <td class="fw-bold">{{ $attempt->score }} / {{ $totalMarks }}</td>
                                <td>{{ $percentage }}%</td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ $status }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $levelClass }}">{{ $level }}</span>
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
                                    {{ __('app.no_students_attempted_exam') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            {{ __('app.select_exam_to_show_report') }}
        </div>
    @endif
</div>
@endsection