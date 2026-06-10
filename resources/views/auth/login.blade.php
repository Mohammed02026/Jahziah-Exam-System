@extends('layouts.app')

@section('title', __('auth.login'))

@section('content')
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center bg-light">

    <div class="row shadow rounded overflow-hidden" style="width: 85%; max-width: 1100px;">

        <!-- LEFT SIDE (IMAGE FULL HEIGHT NO GAP) -->
        <div class="col-md-6 p-0 d-flex">

            <img src="{{ asset('assets/images/login-bg.jpg') }}"
                 alt="Login Background"
                 style="
                    width: 100%;
                    height: 100%;
                     
                    object-position: center;
                 ">
        </div>

        <!-- RIGHT SIDE (FORM SAME HEIGHT) -->
        <div class="col-md-6 d-flex align-items-center justify-content-center bg-white">

            <div style="width: 80%; max-width: 350px;">

                <!-- Logo -->
                <div class="text-center mb-3">
                    <img src="{{ asset('assets/logo.png') }}" style="height:70px;">
                </div>

                <h4 class="mb-3 text-center">{{ __('auth.login') }}</h4>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('auth.email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('auth.password') }}</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button class="btn btn-primary w-100">{{ __('auth.login') }}</button>

                    <div class="text-center mt-3">
                        <a href="{{ route('register') }}">{{ __('auth.register') }}</a>
                    </div>
                </form>

            </div>
        </div>

    </div>

</div>
@endsection