<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ResumeForge') }}</title>
    @vite(['resources/css/app.css'])
    <style>
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 24px;
        }
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }
        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 2.5rem;
        }
    </style>
</head>
<body class="antialiased">
    <div class="hero">
        <div class="animate-fade-in">
            <div class="inline-block px-4 py-1.5 rounded-full border border-violet-500/30 bg-violet-500/10 text-violet-400 font-medium text-sm mb-6">
                Now with AI-Free Built-in ATS Scoring
            </div>
            <h1 class="hero-title">
                Build a <span class="text-gradient">Premium</span> Resume<br>
                That Beats the ATS
            </h1>
            <p class="hero-subtitle">
                Create beautifully designed, ATS-optimized resumes. Compare versions, match against job descriptions, and export to PDF instantly.
            </p>
            <div class="flex justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 16px 32px; font-size: 1.1rem;">Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 16px 32px; font-size: 1.1rem;">Start Building Free</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary" style="padding: 16px 32px; font-size: 1.1rem;">Log In</a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
