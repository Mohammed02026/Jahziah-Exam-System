@extends('layouts.admin')

@section('title', __('app.admin_dashboard'))

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="m-0 fw-bold">{{ __('app.admin_dashboard') }}</h2>
            <div class="text-muted small">
                {{ now()->format('Y-m-d H:i') }}
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-md-4 col-xl-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.users') }}</div>
                            <div class="display-6 fw-bold">{{ $counts['users'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.all_users_in_system') ?? 'All users in system' }}
                            </div>
                        </div>
                        <div class="fs-2 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>

                    <a class="btn btn-sm btn-primary mt-3" href="{{ route('admin.users.index') }}">
                        {{ __('app.manage_users') ?? __('app.users_management') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.students') ?? 'Students' }}</div>
                            <div class="display-6 fw-bold">{{ $counts['students'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.students_accounts_count') ?? 'Students accounts count' }}
                            </div>
                        </div>
                        <div class="fs-2 text-success">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.instructors') ?? 'Instructors' }}</div>
                            <div class="display-6 fw-bold">{{ $counts['instructors'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.instructors_accounts_count') ?? 'Instructors accounts count' }}
                            </div>
                        </div>
                        <div class="fs-2 text-warning">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.exams') }}</div>
                            <div class="display-6 fw-bold">{{ $counts['exams'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.exams_count') ?? 'Exams count' }}
                            </div>
                        </div>
                        <div class="fs-2 text-danger">
                            <i class="bi bi-journal-text"></i>
                        </div>
                    </div>

                    <a class="btn btn-sm btn-outline-secondary mt-3" href="{{ route('admin.reports.index') }}">
                        {{ __('app.reports') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.questions') }}</div>
                            <div class="display-6 fw-bold">{{ $counts['questions'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.question_bank') ?? 'Question bank' }}
                            </div>
                        </div>
                        <div class="fs-2 text-info">
                            <i class="bi bi-patch-question-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small">{{ __('app.attempts') }}</div>
                            <div class="display-6 fw-bold">{{ $counts['attempts'] ?? 0 }}</div>
                            <div class="text-muted small">
                                {{ __('app.students_attempts') ?? 'Student attempts' }}
                            </div>
                        </div>
                        <div class="fs-2 text-secondary">
                            <i class="bi bi-clipboard-data-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="mb-3">{{ __('app.shortcuts') }}</h5>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                    <i class="bi bi-person-plus-fill me-1"></i>
                    {{ __('app.new_user') }}
                </a>

                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-people-fill me-1"></i>
                    {{ __('app.users') }}
                </a>

                <a href="{{ route('admin.reports.index') }}" class="btn btn-primary">
                    <i class="bi bi-bar-chart-fill me-1"></i>
                    {{ __('app.reports') }}
                </a>
            </div>
        </div>
    </div>

</div>
@endsection