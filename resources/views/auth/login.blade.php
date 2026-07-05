<x-guest-layout>
    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-subtitle">Sign in to your ResumeForge account</p>

    @if (session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" id="remember_me" class="form-checkbox">
                <span class="text-sm text-muted">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm" style="color: var(--accent-cyan);">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-full" style="padding: 14px;">Sign In</button>

        <p class="text-center text-muted mt-6 text-sm">
            Don't have an account? <a href="{{ route('register') }}" style="color: var(--accent-cyan);">Create one free</a>
        </p>
    </form>
</x-guest-layout>
