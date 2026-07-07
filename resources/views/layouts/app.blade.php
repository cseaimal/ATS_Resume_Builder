<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ResumeForge') }} — @yield('title', 'Dashboard')</title>
    <meta name="description" content="Build ATS-optimized resumes that get you hired.">
    <link rel="icon" type="image/png" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* App layout ambient */
        body::before {
            background:
                radial-gradient(ellipse 60% 40% at 5% 0%, rgba(139,92,246,0.1) 0%, transparent 60%),
                radial-gradient(ellipse 50% 35% at 95% 100%, rgba(6,214,160,0.08) 0%, transparent 60%);
        }

        /* User avatar initials */
        .nav-avatar {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #7c3aed, #06d6a0);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.02em;
            box-shadow: 0 0 14px rgba(124,58,237,0.35);
            flex-shrink: 0;
        }

        /* Active nav underline */
        .nav-link.active {
            color: var(--text-primary) !important;
            position: relative;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 50%; right: 50%;
            height: 2px;
            background: linear-gradient(90deg, var(--violet), var(--cyan));
            border-radius: 2px;
            transition: left 0.3s ease, right 0.3s ease;
            left: 14px; right: 14px;
        }

        /* Flash message container */
        .flash-container {
            padding: 0 32px;
            max-width: 1300px;
            margin: 20px auto 0;
        }
    </style>
</head>
<body>
    <!-- Premium Navbar -->
    <nav class="navbar">
        <div class="container navbar-inner">
            <!-- Brand -->
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="nav-brand text-gradient" style="text-decoration:none;">
                    <div class="nav-brand-icon">⚡</div>
                    ResumeForge
                </a>

                @auth
                <div class="nav-links" style="margin-left: 8px;">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard.ats-compare') }}"
                       class="nav-link {{ request()->routeIs('dashboard.ats-compare') ? 'active' : '' }}">
                        ATS Compare
                    </a>
                </div>
                @endauth
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-3">
                @auth
                    <div class="nav-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <span class="nav-link" style="cursor:default; color: var(--text-secondary); font-size:0.88rem;">
                        {{ auth()->user()->name ?? 'User' }}
                    </span>
                    <div style="width:1px; height:22px; background: var(--glass-border); margin: 0 4px;"></div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-ghost" style="padding: 8px 16px; font-size: 0.85rem;">
                            Log Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 9px 20px; font-size: 0.88rem;">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="flash-container">
            <div class="alert alert-success animate-fade-in" style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:1rem;">✓</span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="flash-container">
            <div class="alert alert-danger animate-fade-in">
                <span style="font-size:1rem; flex-shrink:0;">⚠</span>
                <ul style="margin-left: 4px; list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>
