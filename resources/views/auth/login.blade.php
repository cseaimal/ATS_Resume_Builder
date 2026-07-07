<x-guest-layout>
    <style>
        .auth-form-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.07);
        }
        .form-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
        }
        .form-divider::before, .form-divider::after {
            content: ''; flex: 1; height: 1px;
            background: rgba(255,255,255,0.06);
        }
        .form-divider span { font-size: 0.75rem; color: rgba(122,136,168,0.5); font-weight: 500; }
        .form-checkbox-label {
            display: flex; align-items: center; gap: 8px;
            cursor: pointer; user-select: none;
        }
        .form-checkbox-label input { accent-color: #7c3aed; width: 16px; height: 16px; border-radius: 4px; }
    </style>

    {{-- Header --}}
    <div style="margin-bottom: 28px;">
        <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:100px;background:rgba(139,92,246,0.08);border:1px solid rgba(139,92,246,0.2);font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#a78bfa;margin-bottom:14px;">
            ✦ Welcome Back
        </div>
        <h1 class="auth-title">Sign in to your account</h1>
        <p class="auth-subtitle">Resume builder, ATS scores, and PDF export — all yours.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success" style="margin-bottom:20px;">{{ session('status') }}</div>
    @endif

    <div class="auth-form-card">
        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required autofocus autocomplete="username">
                @error('email')
                    <p class="form-error">⚠ {{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label for="password" class="form-label">Password</label>
                <div style="position:relative;">
                    <input id="password" type="password" name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required autocomplete="current-password"
                        style="padding-right: 48px;">
                    <button type="button" onclick="togglePwd('password', this)"
                        style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(122,136,168,0.6);cursor:pointer;padding:4px;font-size:1rem;transition:color 0.2s;" title="Toggle password">
                        👁
                    </button>
                </div>
                @error('password')
                    <p class="form-error">⚠ {{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between" style="margin-bottom: 22px;">
                <label class="form-checkbox-label">
                    <input type="checkbox" name="remember" id="remember_me">
                    <span style="font-size:0.85rem; color:rgba(122,136,168,0.9);">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        style="font-size:0.85rem;color:var(--cyan);transition:all 0.2s ease;"
                        onmouseover="this.style.color='var(--cyan-light)'"
                        onmouseout="this.style.color='var(--cyan)'">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary w-full" style="padding:14px;font-size:0.97rem;border-radius:100px;" id="login-btn">
                <span id="login-btn-text">Sign In</span>
                <span id="login-btn-loading" style="display:none;">Signing in…</span>
            </button>
        </form>
    </div>

    <p style="text-align:center;font-size:0.88rem;color:rgba(122,136,168,0.8);margin-top:16px;">
        No account yet?
        <a href="{{ route('register') }}" style="color:var(--cyan);font-weight:600;">
            Create one free →
        </a>
    </p>

    <script>
        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                btn.style.opacity = '1';
            } else {
                input.type = 'password';
                btn.style.opacity = '0.6';
            }
        }
        document.getElementById('login-form').addEventListener('submit', function() {
            document.getElementById('login-btn-text').style.display = 'none';
            document.getElementById('login-btn-loading').style.display = 'inline';
            document.getElementById('login-btn').disabled = true;
        });
    </script>
</x-guest-layout>
