@extends('layouts.admin')

@section('title', __('app.user_details'))

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="m-0 fw-bold">{{ __('app.user_details') }}</h2>
            <div class="text-muted small">
                {{ __('app.users') }} / {{ __('app.user_details') }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">
                <i class="bi bi-arrow-left me-1"></i>
                {{ __('app.back') }}
            </a>

            <a class="btn btn-primary" href="{{ route('admin.users.edit', $user) }}">
                <i class="bi bi-pencil-square me-1"></i>
                {{ __('app.edit') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-muted small">{{ __('app.id') }}</div>
                    <div class="fw-bold fs-5">{{ $user->id }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">{{ __('app.role') }}</div>
                    <div class="fw-bold fs-5">
                        <span class="badge
                            @if(($user->role?->value ?? $user->role) === 'admin') bg-danger
                            @elseif(($user->role?->value ?? $user->role) === 'instructor') bg-warning text-dark
                            @else bg-primary
                            @endif">
                            {{ $user->role?->value ?? $user->role }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">{{ __('app.name') }}</div>
                    <div class="fw-bold fs-5">{{ $user->name }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">{{ __('app.email') }}</div>
                    <div class="fw-bold fs-5">{{ $user->email }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">{{ __('app.created_at') }}</div>
                    <div class="fw-bold">{{ $user->created_at?->format('Y-m-d H:i') }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">{{ __('app.updated_at') }}</div>
                    <div class="fw-bold">{{ $user->updated_at?->format('Y-m-d H:i') }}</div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <a class="btn btn-outline-primary" href="{{ route('admin.users.edit', $user) }}">
                    <i class="bi bi-pencil-square me-1"></i>
                    {{ __('app.edit') }}
                </a>

                <form method="POST"
                      action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('{{ __('app.confirm_delete_user') }}');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>
                        {{ __('app.delete_user') }}
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection