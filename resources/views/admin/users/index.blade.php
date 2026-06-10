@extends('layouts.admin')

@section('title', __('app.users'))

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="m-0 fw-bold">{{ __('app.users_management') }}</h2>
            <div class="text-muted small">
                {{ __('app.users') }} / {{ __('app.users_management') }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ url()->previous() }}">
                <i class="bi bi-arrow-left me-1"></i>
                {{ __('app.back') }}
            </a>

            <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                <i class="bi bi-person-plus-fill me-1"></i>
                {{ __('app.new_user') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-10">
                    <label class="form-label">{{ __('app.search') }}</label>
                    <input type="text"
                           name="q"
                           value="{{ $q }}"
                           class="form-control"
                           placeholder="{{ __('app.search_name_email') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>
                        {{ __('app.search') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.email') }}</th>
                        <th>{{ __('app.role') }}</th>
                        <th>{{ __('app.created_at') }}</th>
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td class="fw-semibold">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="badge
                                    @if(($u->role?->value ?? $u->role) === 'admin') bg-danger
                                    @elseif(($u->role?->value ?? $u->role) === 'instructor') bg-warning text-dark
                                    @else bg-primary
                                    @endif">
                                    {{ $u->role?->value ?? $u->role }}
                                </span>
                            </td>
                            <td>{{ $u->created_at?->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="{{ route('admin.users.show', $u) }}">
                                    <i class="bi bi-eye me-1"></i>
                                    {{ __('app.view') }}
                                </a>

                                <a class="btn btn-sm btn-outline-secondary"
                                   href="{{ route('admin.users.edit', $u) }}">
                                    <i class="bi bi-pencil-square me-1"></i>
                                    {{ __('app.edit') }}
                                </a>

                                <form class="d-inline"
                                      method="POST"
                                      action="{{ route('admin.users.destroy', $u) }}"
                                      onsubmit="return confirm('{{ __('app.confirm_delete') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i>
                                        {{ __('app.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-4">
                                {{ __('app.no_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection