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
        .pw-strength {
            height: 3px;
            border-radius: 100px;
            margin-top: 8px;
            background: rgba(255,255,255,0.06);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .pw-strength-bar {
            height: 100%;
            border-radius: 100px;
            width: 0%;
            transition: width 0.4s ease, background 0.4s ease;
        }
        .pw-strength-label {
            font-size: 0.72rem;
            margin-top: 4px;
            font-weight: 600;
            transition: color 0.3s ease;
        }
    </style>

    {{-- Header --}}
    <div style="margin-bottom: 28px;">
        <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:100px;background:rgba(6,214,160,0.08);border:1px solid rgba(6,214,160,0.2);font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#06d6a0;margin-bottom:14px;">
            ✦ Get Started Free
        </div>
        <h1 class="auth-title">Create your account</h1>
        <p class="auth-subtitle">Start building ATS-optimized resumes in minutes — no credit card needed.</p>
    </div>

    <div class="auth-form-card">
        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" type="text" name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Alex Johnson"
                    required autofocus autocomplete="name">
                @error('name')
                    <p class="form-error">⚠ {{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required autocomplete="username">
                @error('email')
                    <p class="form-error">⚠ {{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div style="position:relative;">
                    <input id="password" type="password" name="password"
                        class="form-control"
                        placeholder="Min. 8 characters"
                        required autocomplete="new-password"
                        style="padding-right:48px;"
                        oninput="checkPwStrength(this.value)">
                    <button type="button" onclick="togglePwd('password', this)"
                        style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(122,136,168,0.6);cursor:pointer;padding:4px;font-size:1rem;" title="Toggle">👁</button>
                </div>
                <div class="pw-strength"><div class="pw-strength-bar" id="pw-bar"></div></div>
                <div class="pw-strength-label" id="pw-label" style="color:rgba(122,136,168,0.5);">Enter a password</div>
                @error('password')
                    <p class="form-error">⚠ {{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 22px;">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div style="position:relative;">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control"
                        placeholder="••••••••"
                        required autocomplete="new-password"
                        style="padding-right:48px;">
                    <button type="button" onclick="togglePwd('password_confirmation', this)"
                        style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(122,136,168,0.6);cursor:pointer;padding:4px;font-size:1rem;" title="Toggle">👁</button>
                </div>
                @error('password_confirmation')
                    <p class="form-error">⚠ {{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full" style="padding:14px;font-size:0.97rem;border-radius:100px;" id="reg-btn">
                <span id="reg-btn-text">⚡ Create Free Account</span>
                <span id="reg-btn-loading" style="display:none;">Creating account…</span>
            </button>

            <p style="text-align:center;font-size:0.78rem;color:rgba(122,136,168,0.5);margin-top:14px;line-height:1.5;">
                By registering, you agree to use this tool responsibly. No spam, ever.
            </p>
        </form>
    </div>

    <p style="text-align:center;font-size:0.88rem;color:rgba(122,136,168,0.8);margin-top:16px;">
        Already have an account?
        <a href="{{ route('login') }}" style="color:var(--cyan);font-weight:600;">Sign in →</a>
    </p>

    <script>
        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.style.opacity = input.type === 'text' ? '1' : '0.6';
        }

        function checkPwStrength(pw) {
            const bar = document.getElementById('pw-bar');
            const label = document.getElementById('pw-label');
            let strength = 0;
            if (pw.length >= 8)   strength++;
            if (/[A-Z]/.test(pw)) strength++;
            if (/[0-9]/.test(pw)) strength++;
            if (/[^A-Za-z0-9]/.test(pw)) strength++;

            const levels = [
                { pct:'25%', color:'#ef4444', text:'Weak' },
                { pct:'50%', color:'#f59e0b', text:'Fair' },
                { pct:'75%', color:'#3b82f6', text:'Good' },
                { pct:'100%', color:'#10b981', text:'Strong' },
            ];
            const level = levels[strength - 1] || { pct:'0%', color:'transparent', text:'Enter a password' };
            bar.style.width = level.pct;
            bar.style.background = level.color;
            bar.style.boxShadow = strength > 0 ? `0 0 8px ${level.color}` : 'none';
            label.style.color = strength > 0 ? level.color : 'rgba(122,136,168,0.5)';
            label.textContent = level.text;
        }

        document.getElementById('register-form').addEventListener('submit', function() {
            document.getElementById('reg-btn-text').style.display = 'none';
            document.getElementById('reg-btn-loading').style.display = 'inline';
            document.getElementById('reg-btn').disabled = true;
        });
    </script>
</x-guest-layout>
