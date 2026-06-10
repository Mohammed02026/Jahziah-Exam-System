@extends('layouts.app')

@section('title', __('auth.register'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    {{-- ✅ Logo --}}
                    <div class="text-center mb-3">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="height:110px; width:auto;">
                    </div>

                    <h3 class="mb-3 text-center">{{ __('auth.register') }}</h3>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                        </div>

                        {{-- الدور ثابت: Student فقط --}}
                        <input type="hidden" name="role" value="student">
                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.role') }}</label>
                            <input type="text" class="form-control" value="{{ __('app.roles.student') }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.password') }}</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button class="btn btn-success w-100">{{ __('auth.register') }}</button>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}">{{ __('auth.login') }}</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection