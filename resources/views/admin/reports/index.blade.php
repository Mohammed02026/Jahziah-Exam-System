@extends('layouts.admin')

@section('title', __('app.reports'))

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="m-0 fw-bold">{{ __('app.system_reports') }}</h2>
            <div class="text-muted small">
                {{ __('app.reports') }} / {{ __('app.system_reports') }}
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-arrow-left me-1"></i>
            {{ __('app.back_to_dashboard') }}
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET" action="{{ route('admin.reports.index') }}">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.from_date') }}</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.to_date') }}</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-funnel-fill me-1"></i>
                        {{ __('app.apply_filter') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.users') }}</div>
                            <div class="display-6 fw-bold">{{ $summary['users'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.report_students') }}
                            </div>
                        </div>
                        <div class="fs-2 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>

                    <a class="btn btn-sm btn-outline-primary mt-3"
                       href="{{ route('admin.reports.show', ['type' => 'students', 'from' => $from, 'to' => $to]) }}">
                        {{ __('app.report_students') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.exams') }}</div>
                            <div class="display-6 fw-bold">{{ $summary['exams'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.report_exams') }}
                            </div>
                        </div>
                        <div class="fs-2 text-danger">
                            <i class="bi bi-journal-text"></i>
                        </div>
                    </div>

                    <a class="btn btn-sm btn-outline-primary mt-3"
                       href="{{ route('admin.reports.show', ['type' => 'exams', 'from' => $from, 'to' => $to]) }}">
                        {{ __('app.report_exams') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.questions') }}</div>
                            <div class="display-6 fw-bold">{{ $summary['questions'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.total_questions_in_bank') }}
                            </div>
                        </div>
                        <div class="fs-2 text-info">
                            <i class="bi bi-patch-question-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.attempts') }}</div>
                            <div class="display-6 fw-bold">{{ $summary['attempts'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.avg_score') }}: {{ $summary['avg_score'] ?? 0 }}
                            </div>
                        </div>
                        <div class="fs-2 text-success">
                            <i class="bi bi-clipboard-data-fill"></i>
                        </div>
                    </div>

                    <a class="btn btn-sm btn-outline-primary mt-3"
                       href="{{ route('admin.reports.show', ['type' => 'attempts', 'from' => $from, 'to' => $to]) }}">
                        {{ __('app.report_attempts') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="mb-3">{{ __('app.report_types') }}</h5>

            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-primary"
                   href="{{ route('admin.reports.show', ['type' => 'students', 'from' => $from, 'to' => $to]) }}">
                    <i class="bi bi-people-fill me-1"></i>
                    {{ __('app.report_students') }}
                </a>

                <a class="btn btn-outline-primary"
                   href="{{ route('admin.reports.show', ['type' => 'exams', 'from' => $from, 'to' => $to]) }}">
                    <i class="bi bi-journal-text me-1"></i>
                    {{ __('app.report_exams') }}
                </a>

                <a class="btn btn-outline-primary"
                   href="{{ route('admin.reports.show', ['type' => 'attempts', 'from' => $from, 'to' => $to]) }}">
                    <i class="bi bi-clipboard-data-fill me-1"></i>
                    {{ __('app.report_attempts') }}
                </a>
            </div>
        </div>
    </div>

</div>
@endsection