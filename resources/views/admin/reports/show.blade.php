@extends('layouts.admin')

@section('title', __('app.report_details'))

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="m-0 fw-bold">{{ __('app.report') }}: {{ ucfirst($type) }}</h2>
            <div class="text-muted small">
                {{ __('app.report_details') }}
            </div>
        </div>

        <a class="btn btn-outline-secondary"
           href="{{ route('admin.reports.index', ['from' => $from, 'to' => $to]) }}">
            <i class="bi bi-arrow-left me-1"></i>
            {{ __('app.back') }}
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="text-muted small">
                {{ __('app.filter') }}:
                {{ __('app.filter_from') ?? 'from' }}
                <b>{{ $from ?: '—' }}</b>
                {{ __('app.filter_to') ?? 'to' }}
                <b>{{ $to ?: '—' }}</b>
            </div>
        </div>
    </div>

    @if(!empty($report['note']))
        <div class="alert alert-warning">
            {{ $report['note'] }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @php($items = $report['items'] ?? collect())

            @if($items->isEmpty())
                <div class="text-muted">
                    {{ __('app.no_data') ?? __('app.no_details') }}
                </div>
            @else

                @if($type === 'students')
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('app.name') ?? 'Name' }}</th>
                                    <th>{{ __('app.email') ?? 'Email' }}</th>
                                    <th>{{ __('app.role') ?? 'Role' }}</th>
                                    <th>{{ __('app.created_at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $u)
                                    <tr>
                                        <td>{{ $u->id }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $u->role?->value ?? $u->role }}
                                            </span>
                                        </td>
                                        <td>{{ $u->created_at?->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($type === 'exams')
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('app.title') }}</th>
                                    <th>{{ __('app.course') }}</th>
                                    <th>{{ __('app.status') }}</th>
                                    <th>{{ __('app.duration') }}</th>
                                    <th>{{ __('app.total_marks') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $e)
                                    <tr>
                                        <td>{{ $e->id }}</td>
                                        <td>{{ $e->title }}</td>
                                        <td>{{ $e->course?->name }}</td>
                                        <td>
                                            <span class="badge {{ $e->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $e->status }}
                                            </span>
                                        </td>
                                        <td>{{ $e->duration_minutes }} {{ __('app.minutes') }}</td>
                                        <td>{{ $e->total_marks }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($type === 'attempts')
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('app.student') }}</th>
                                    <th>{{ __('app.exam') }}</th>
                                    <th>{{ __('app.status') }}</th>
                                    <th>{{ __('app.score') }}</th>
                                    <th>{{ __('app.submitted') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $a)
                                    <tr>
                                        <td>{{ $a->id }}</td>
                                        <td>{{ $a->student?->name }} ({{ $a->student?->email }})</td>
                                        <td>{{ $a->exam?->title }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $a->status?->value ?? $a->status }}
                                            </span>
                                        </td>
                                        <td>{{ $a->score }}</td>
                                        <td>{{ $a->submitted_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else
                    <pre class="mb-0">{{ json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @endif

            @endif
        </div>
    </div>

</div>
@endsection