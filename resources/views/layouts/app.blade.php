<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'Jahziah Guidance'))</title>

    {{-- Bootstrap 5 CDN --}}
    @if(app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    {{-- Font (clean academic) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --brand: #60a5fa;      /* primary (light blue) */
            --brand-600:#3b82f6;   /* hover/darker */
            --accent:#93c5fd;      /* accent (softer blue) */
            --ink:#0f172a;
            --muted:#64748b;

            --bg:#f3f8ff;          /* background */
            --panel: rgba(255,255,255,.78);
            --panel-solid:#ffffff;
            --border: rgba(148,163,184,.28);

            --radius: 18px;
            --radius-sm: 14px;
            --shadow: 0 14px 32px rgba(2,6,23,.10);
            --shadow-sm: 0 10px 22px rgba(2,6,23,.08);
        }

        html, body { height: 100%; }
        body{
            font-family: "Tajawal", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(900px 450px at 15% 0%, rgba(96,165,250,.16), transparent 60%),
                radial-gradient(700px 380px at 100% 10%, rgba(147,197,253,.14), transparent 60%),
                linear-gradient(180deg, #f7fbff 0%, var(--bg) 100%);
        }

        /* Navbar: calm, glassy */
        .app-navbar{
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(255,255,255,.75);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
        }
        .app-navbar .navbar-brand{
            font-weight: 800;
            letter-spacing: .2px;
            color: var(--ink);
        }
        .brand-dot{
            display:inline-block;
            width:10px; height:10px;
            border-radius: 999px;
            background: var(--brand);
            margin-inline-start:.45rem;
            box-shadow: 0 6px 16px rgba(96,165,250,.22);
        }
        .app-navbar .nav-link{
            font-weight: 700;
            color: #334155;
            border-radius: 12px;
            padding: .45rem .65rem;
        }
        .app-navbar .nav-link:hover{
            background: rgba(96,165,250,.12);
            color: var(--brand-600);
        }

        /* Global surfaces */
        .app-container{
            padding: 1.25rem 0 2rem;
        }
        .app-card{
            background: var(--panel-solid);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        /* Bootstrap tweaks */
        .card{
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .form-control, .form-select{
            border-radius: 14px;
            border-color: rgba(148,163,184,.35);
            padding: .65rem .85rem;
        }
        .form-control:focus, .form-select:focus{
            border-color: rgba(96,165,250,.55);
            box-shadow: 0 0 0 .22rem rgba(96,165,250,.16);
        }
        .btn{
            border-radius: 14px;
            font-weight: 800;
            padding: .55rem .9rem;
        }
        .btn-primary{
            background: var(--brand);
            border-color: var(--brand);
            box-shadow: 0 12px 22px rgba(96,165,250,.20);
        }
        .btn-primary:hover{
            background: var(--brand-600);
            border-color: var(--brand-600);
        }
        .btn-outline-danger{
            border-color: rgba(239,68,68,.55);
            color: #ef4444;
        }
        .btn-outline-danger:hover{
            background: #ef4444;
            border-color: #ef4444;
        }

        .dropdown-menu{
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .dropdown-item{ font-weight: 700; }

        .badge{
            border-radius: 999px;
            font-weight: 800;
            padding: .4rem .65rem;
        }

        .text-muted{ color: var(--muted) !important; }

        /* Cleaner tables */
        .table{
            border-color: rgba(148,163,184,.22);
        }
        .table thead th{
            font-weight: 900;
            color: #334155;
        }

        /* Pagination (bootstrap) nicer */
        .pagination .page-link{
            border-radius: 12px;
            margin: 0 .15rem;
            font-weight: 800;
        }

        /* =========================
           FINAL FIX: Pagination in RTL
           ========================= */
        html[dir="rtl"] .pagination,
        [dir="rtl"] .pagination{
            direction: ltr !important;
            display: flex !important;
            flex-direction: row !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            gap: .25rem;
            unicode-bidi: plaintext !important;
        }

        html[dir="rtl"] .pagination .page-link,
        [dir="rtl"] .pagination .page-link{
            direction: ltr !important;
        }

        /* Small utility */
        .chip{
            display:inline-flex;
            align-items:center;
            gap:.45rem;
            padding:.35rem .65rem;
            border-radius: 999px;
            background: rgba(96,165,250,.12);
            border: 1px solid rgba(96,165,250,.18);
            color: var(--brand-600);
            font-weight: 800;
            font-size: .9rem;
        }

        /* =========================
           Bigger, more comfortable UI
           ========================= */

        html { font-size: 17px; }
        @media (max-width: 576px){ html { font-size: 16px; } }

        .card{
            border-radius: 22px !important;
            --bs-card-spacer-y: 1.35rem;
            --bs-card-spacer-x: 1.35rem;
        }
        .card .card-header{
            padding: 1rem 1.35rem;
            font-weight: 900;
        }
        .card .card-body{
            padding: 1.35rem 1.35rem;
        }

        .form-label{
            font-weight: 800;
            color: #334155;
            margin-bottom: .45rem;
        }
        .form-control, .form-select{
            padding: .9rem 1rem !important;
            font-size: 1rem !important;
            border-radius: 16px !important;
        }

        .btn{
            padding: .75rem 1.15rem !important;
            font-size: 1rem !important;
            border-radius: 16px !important;
        }
        .btn-sm{
            padding: .55rem .9rem !important;
            font-size: .95rem !important;
            border-radius: 14px !important;
        }

        .alert{
            padding: 1rem 1.15rem;
            border-radius: 18px;
        }

        .table > :not(caption) > * > *{
            padding: .85rem .85rem;
        }
    </style>

    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container">

        {{-- ✅ Logo + App name --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home.redirect') }}">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="height:60px; width:auto;">
            <span>{{ __('app.app_name') }}</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTop">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTop">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home.redirect') }}">{{ __('app.home') }}</a>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                @php($locale = app()->getLocale())

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle chip" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        🌐 {{ $locale === 'ar' ? 'العربية' : 'English' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                    </ul>
                </li>

                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('auth.register') }}</a></li>
                @else
                    @php($roleValue = auth()->user()->role?->value ?? auth()->user()->role)
                    <li class="nav-item d-none d-lg-block">
                        <span class="chip">👤 {{ auth()->user()->name }} <span class="text-muted" style="font-weight:800">({{ $roleValue }})</span></span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">{{ __('auth.logout') }}</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="app-container">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>