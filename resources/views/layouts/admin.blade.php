<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', __('app.admin_panel'))</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    @if(app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1e293b;
            color: #fff;
            position: fixed;
            top: 0;
        }

        html[dir="ltr"] .sidebar {
            left: 0;
        }

        html[dir="rtl"] .sidebar {
            right: 0;
        }

        .sidebar a,
        .sidebar button {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            transition: 0.2s;
            width: 100%;
            text-align: start;
            border: none;
            background: none;
        }

        .sidebar a:hover,
        .sidebar a.active,
        .sidebar button:hover {
            background: #334155;
            color: #fff;
        }

        .sidebar .logo {
            font-size: 20px;
            font-weight: bold;
            padding: 20px;
            border-bottom: 1px solid #334155;
        }

        .content {
            padding: 20px;
        }

        html[dir="ltr"] .content {
            margin-left: 240px;
            margin-right: 0;
        }

        html[dir="rtl"] .content {
            margin-right: 240px;
            margin-left: 0;
        }

        .topbar {
            background: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .stat-card {
            padding: 20px;
            color: #fff;
            border-radius: 12px;
        }

        .bg-blue { background: #3b82f6; }
        .bg-green { background: #10b981; }
        .bg-orange { background: #f59e0b; }
        .bg-red { background: #ef4444; }

        .topbar .dropdown-toggle {
            border-radius: 10px;
            font-weight: 600;
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="sidebar">
    <div class="logo">
        {{ __('app.admin_panel') }}
    </div>

    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> {{ __('app.admin_dashboard') }}
    </a>

    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> {{ __('app.users') }}
    </a>

    <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart"></i> {{ __('app.reports') }}
    </a>

    <hr class="text-secondary">

    <a href="{{ route('home.redirect') }}">
        <i class="bi bi-house"></i> {{ __('app.main_site') }}
    </a>

    <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> {{ __('auth.logout') }}
        </button>
    </form>
</div>

<div class="content">

    <div class="topbar shadow-sm">
        <div>
            <b>@yield('title')</b>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    🌐 {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a>
                    </li>
                </ul>
            </div>

            <div>
                👤 {{ auth()->user()->name ?? 'Admin' }}
            </div>
        </div>
    </div>

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>