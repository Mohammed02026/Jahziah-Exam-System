@extends('layouts.admin')

@section('title', __('app.edit_user'))

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="m-0 fw-bold">{{ __('app.edit_user') }} #{{ $user->id }}</h2>
            <div class="text-muted small">
                {{ __('app.users') }} / {{ __('app.edit_user') }}
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="{{ route('admin.users.show', $user) }}">
            <i class="bi bi-arrow-left me-1"></i>
            {{ __('app.back') }}
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">{{ __('app.validation_errors') }}</div>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.name') }}</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.email') }}</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.role') }}</label>
                        <select name="role" class="form-select" required>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(old('role', $user->role?->value) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.new_password_optional') }}</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="{{ __('app.leave_blank_keep_current') }}">
                        <div class="form-text text-muted">
                            {{ __('app.leave_blank_keep_current') }}
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        {{ __('app.update') }}
                    </button>

                    <a class="btn btn-outline-secondary"
                       href="{{ route('admin.users.show', $user) }}">
                        {{ __('app.cancel') ?? __('app.back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection