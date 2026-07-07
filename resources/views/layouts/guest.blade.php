<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ResumeForge') }}</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left">
            <a href="/" class="auth-brand text-gradient">ResumeForge</a>
            <div class="auth-tagline">
                <h2>Build resumes that<br>beat the ATS.</h2>
                <p>Create ATS-optimized resumes, test against job descriptions, and export to PDF — all for free.</p>
                <div class="auth-features">
                    <div class="auth-feature">
                        <span class="auth-feature-icon">✓</span>
                        <span>Built-in ATS keyword scoring engine</span>
                    </div>
                    <div class="auth-feature">
                        <span class="auth-feature-icon">✓</span>
                        <span>Multi-version resume management</span>
                    </div>
                    <div class="auth-feature">
                        <span class="auth-feature-icon">✓</span>
                        <span>Live preview + one-click PDF export</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-right">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
