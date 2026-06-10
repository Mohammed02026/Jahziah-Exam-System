@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $role = $user?->role;
    $roleValue = $role?->value ?? $role;
    $locale = app()->getLocale();
    $isAr = $locale === 'ar';
@endphp

<style>
    :root{
        --dash-border: rgba(148,163,184,.28);
        --dash-shadow: 0 18px 40px rgba(2,6,23,.10);
        --dash-radius: 24px;

        --side-bg: rgba(255,255,255,.78);
--side-hover: rgba(109,40,217,.08);
--side-active: rgba(109,40,217,.12);
        --side-text: #0f172a;
        --side-muted:#64748b;
    }

    .dash-wrap{ padding: 0 1.5rem 2.25rem; }

    .dash-shell{
        background: rgba(255,255,255,.55);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius);
        box-shadow: var(--dash-shadow);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .dash-sidebar{
        background: var(--side-bg);
        border-{{ $isAr ? 'left' : 'right' }}: 1px solid var(--dash-border);
        min-height: calc(100vh - 160px);
        padding: 1.35rem !important;
    }

    .side-head .app-name{
        font-weight: 950;
        color: var(--side-text);
        line-height: 1.1;
        font-size: 1.15rem;
    }
    .side-head .user-name{
        color: var(--side-muted);
        font-weight: 800;
        font-size: 1rem;
    }
    .role-chip{
        display:inline-flex;
        align-items:center;
        gap:.35rem;
        padding:.35rem .65rem;
        border-radius: 999px;
        background: rgba(37,99,235,.10);
        border: 1px solid rgba(37,99,235,.18);
        color:#1d4ed8;
        font-weight: 900;
        font-size: .9rem;
    }

    .nav-group{ margin-top: 1rem; display:flex; flex-direction:column; gap:.35rem; }

    .nav-link-x{
        display:flex;
        align-items:center;
        gap:.65rem;
        padding: .85rem .95rem;
        border-radius: 16px;
        text-decoration:none;
        color: var(--side-text);
        font-weight: 900;
        border: 1px solid transparent;
        transition: all .14s ease;
        font-size: 1rem;
    }
    .nav-link-x:hover{
        background: var(--side-hover);
        border-color: rgba(37,99,235,.12);
        color:#1d4ed8;
    }
    .nav-link-x.active{
        background: var(--side-active);
        border-color: rgba(37,99,235,.22);
        color:#1d4ed8;
    }
    .nav-ico{
        width: 36px;
        height: 36px;
        border-radius: 14px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background: rgba(15,23,42,.06);
        font-size: 18px;
    }

    .divider{ border-top: 1px solid rgba(148,163,184,.22); margin: 1rem 0; }

    .dash-main{
        min-height: calc(100vh - 160px);
        padding: 1.2rem 1.4rem !important;
    }
    .dash-header{
        background: rgba(255,255,255,.65);
        border: 1px solid var(--dash-border);
        border-radius: 20px;
        padding: 1.2rem 1.3rem;
        box-shadow: 0 10px 22px rgba(2,6,23,.07);
        backdrop-filter: blur(10px);
        margin-bottom: 1rem;
    }
    .dash-header h3{
        font-weight: 950;
        margin:0;
        font-size: 1.35rem;
    }
    .dash-header .sub{
        color:#64748b;
        font-weight:800;
        font-size:.9rem;
        margin-top: .25rem;
    }

    @media (max-width: 992px){
        .dash-wrap{ padding: 0 1rem 1.5rem; }
        .dash-sidebar, .dash-main{ min-height:auto; }
        .dash-main{ padding: 1rem !important; }
    }
</style>

<div class="container-fluid dash-wrap" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
    <div class="row g-0 dash-shell">

        {{-- Sidebar --}}
        <aside class="col-lg-3 col-xl-2 dash-sidebar">
            <div class="side-head d-flex justify-content-between align-items-start gap-2">
                <div>
                    <div class="app-name">{{ __('app.app_name') }}</div>
                    <div class="user-name mt-1">{{ $user?->name }}</div>
                    <div class="mt-2">
                        <span class="role-chip">🎓 {{ $roleValue }}</span>
                    </div>
                </div>

                {{-- Language (compact) --}}
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ $isAr ? 'AR' : 'EN' }}
                    </button>
                    <ul class="dropdown-menu {{ $isAr ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                    </ul>
                </div>
            </div>

            <div class="divider"></div>

            @php
                $is = fn(string $pattern) => request()->routeIs($pattern) ? 'active' : '';
            @endphp

            <nav class="nav-group">
                <a class="nav-link-x {{ $is('home.redirect') }}" href="{{ route('home.redirect') }}">
                    <span class="nav-ico">🏠</span> <span>{{ __('app.home') }}</span>
                </a>

                @if(($roleValue ?? '') === 'admin')
                    <a class="nav-link-x {{ $is('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">
                        <span class="nav-ico">📊</span> <span>{{ __('app.admin_dashboard') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('admin.users.*') }}" href="{{ route('admin.users.index') }}">
                        <span class="nav-ico">👤</span> <span>{{ __('app.users') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('admin.reports.*') }}" href="{{ route('admin.reports.index') }}">
                        <span class="nav-ico">📄</span> <span>{{ __('app.reports') }}</span>
                    </a>

                @elseif(($roleValue ?? '') === 'instructor')
                    <a class="nav-link-x {{ $is('instructor.dashboard') }}" href="{{ route('instructor.dashboard') }}">
                        <span class="nav-ico">📊</span> <span>{{ __('app.instructor_dashboard') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('instructor.questions.*') }}" href="{{ route('instructor.questions.index') }}">
                        <span class="nav-ico">❓</span> <span>{{ __('app.questions') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('instructor.exams.*') }}" href="{{ route('instructor.exams.index') }}">
                        <span class="nav-ico">📝</span> <span>{{ __('app.exams') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('instructor.lessons.*') }}" href="{{ route('instructor.lessons.index') }}">
                        <span class="nav-ico">📚</span> <span>{{ __('app.lessons') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('instructor.results.*') }}" href="{{ route('instructor.results.index') }}">
                        <span class="nav-ico">🏁</span> <span>{{ __('app.results') }}</span>
                    </a>

                @else
                    <a class="nav-link-x {{ $is('student.dashboard') }}" href="{{ route('student.dashboard') }}">
                        <span class="nav-ico">📊</span> <span>{{ __('app.student_dashboard') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('student.lessons.*') }}" href="{{ route('student.lessons.index') }}">
                        <span class="nav-ico">📚</span> <span>{{ __('app.lessons') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('student.practice.*') }}" href="{{ route('student.practice.index') }}">
                        <span class="nav-ico">🎯</span> <span>{{ __('app.practice') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('student.exams.*') }}" href="{{ route('student.exams.index') }}">
                        <span class="nav-ico">📝</span> <span>{{ __('app.exams') }}</span>
                    </a>
                    <a class="nav-link-x {{ $is('student.results.*') }}" href="{{ route('student.results.index') }}">
                        <span class="nav-ico">🏁</span> <span>{{ __('app.results') }}</span>
                    </a>
                @endif
            </nav>

            <div class="divider"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-danger w-100 fw-bold">{{ __('auth.logout') }}</button>
            </form>
        </aside>

        {{-- Main --}}
        <section class="col-lg-9 col-xl-10 dash-main">
            <div class="dash-header">
                <h3>@yield('title')</h3>
                <div class="sub">{{ __('app.dashboard_panel') }}</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </section>

    </div>
</div>
@endsection