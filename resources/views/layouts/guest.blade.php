<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ResumeForge') }}</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Particle canvas (decorative) */
        .auth-particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }
        .particle {
            position: absolute;
            width: 2px; height: 2px;
            border-radius: 50%;
            background: rgba(139,92,246,0.5);
            animation: particleFloat linear infinite;
        }
        @keyframes particleFloat {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.3; }
            100% { transform: translateY(-20px) scale(1); opacity: 0; }
        }

        /* Auth left grid lines */
        .auth-left-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            z-index: 0;
        }

        /* Floating tech pill on auth left */
        .auth-tech-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 100px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(240,244,255,0.6);
            animation: floatBadge 5s ease-in-out infinite;
        }
        @keyframes floatBadge {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
        .auth-tech-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #06d6a0;
            box-shadow: 0 0 8px #06d6a0;
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink {
            0%,100% { opacity: 1; }
            50%      { opacity: 0.3; }
        }

        /* Auth progress bar on form */
        .auth-progress {
            height: 3px;
            background: rgba(255,255,255,0.05);
            border-radius: 100px;
            margin-bottom: 28px;
            overflow: hidden;
        }
        .auth-progress-fill {
            height: 100%;
            border-radius: 100px;
            background: linear-gradient(90deg, #7c3aed, #06d6a0);
            box-shadow: 0 0 10px rgba(6,214,160,0.4);
            animation: progressLoad 1.2s ease forwards;
        }
        @keyframes progressLoad {
            from { width: 0%; }
            to   { width: 100%; }
        }

        /* Stars review on auth left */
        .auth-review {
            padding: 18px 20px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            position: relative;
            z-index: 1;
        }
        .auth-review-stars { color: #f59e0b; font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 8px; }
        .auth-review-text { font-size: 0.88rem; color: rgba(240,244,255,0.7); line-height: 1.6; margin-bottom: 12px; }
        .auth-reviewer { display: flex; align-items: center; gap: 10px; }
        .auth-reviewer-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #06d6a0);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; color: white;
        }
        .auth-reviewer-name { font-size: 0.82rem; font-weight: 600; color: rgba(240,244,255,0.85); }
        .auth-reviewer-role { font-size: 0.75rem; color: rgba(122,136,168,0.7); }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- Left Panel -->
        <div class="auth-left">
            <div class="auth-left-grid"></div>

            <!-- Brand -->
            <a href="/" class="auth-brand" style="position: relative; z-index: 1; text-decoration: none;">
                <div class="auth-brand-icon">⚡</div>
                <span class="text-gradient">ResumeForge</span>
            </a>

            <!-- Main tagline -->
            <div class="auth-tagline" style="position: relative; z-index: 1; padding: 32px 0;">
                <div class="auth-tech-badge" style="margin-bottom: 24px;">
                    <span class="auth-tech-dot"></span>
                    AI-Powered ATS Engine Active
                </div>

                <h2 style="letter-spacing: -0.04em; margin-bottom: 16px;">
                    Build resumes that<br>
                    <span style="background:linear-gradient(135deg,#c4b5fd 0%,#06d6a0 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">beat the ATS.</span>
                </h2>

                <p style="color: rgba(122,136,168,0.9); font-size: 0.98rem; margin-bottom: 32px; line-height: 1.8;">
                    Create ATS-optimized resumes, test against job descriptions, and export to PDF — completely free.
                </p>

                <div class="auth-features">
                    <div class="auth-feature">
                        <span class="auth-feature-icon">✓</span>
                        <span>Real-time ATS keyword scoring engine</span>
                    </div>
                    <div class="auth-feature">
                        <span class="auth-feature-icon">✓</span>
                        <span>Manage multiple tailored resume versions</span>
                    </div>
                    <div class="auth-feature">
                        <span class="auth-feature-icon">✓</span>
                        <span>Live preview + one-click PDF export</span>
                    </div>
                    <div class="auth-feature">
                        <span class="auth-feature-icon">✓</span>
                        <span>Premium templates that pass all parsers</span>
                    </div>
                </div>
            </div>

            <!-- Review card -->
            <div style="position: relative; z-index: 1;">
                <div class="auth-review">
                    <div class="auth-review-stars">★★★★★</div>
                    <p class="auth-review-text">"I applied to 20 jobs, heard back from 15. ResumeForge completely changed my job search."</p>
                    <div class="auth-reviewer">
                        <div class="auth-reviewer-avatar">AK</div>
                        <div>
                            <div class="auth-reviewer-name">Alex K.</div>
                            <div class="auth-reviewer-role">Software Engineer at Google</div>
                        </div>
                    </div>
                </div>

                <div class="auth-stats" style="margin-top: 20px;">
                    <div>
                        <div class="auth-stat-value text-gradient">10K+</div>
                        <div class="auth-stat-label">Resumes built</div>
                    </div>
                    <div>
                        <div class="auth-stat-value" style="background:linear-gradient(135deg,#06d6a0,#60a5fa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">92%</div>
                        <div class="auth-stat-label">ATS pass rate</div>
                    </div>
                    <div>
                        <div class="auth-stat-value" style="background:linear-gradient(135deg,#f59e0b,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Free</div>
                        <div class="auth-stat-label">Forever</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel (Form) -->
        <div class="auth-right">
            <div class="auth-card animate-fade-in">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
