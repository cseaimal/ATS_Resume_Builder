<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ResumeForge — AI-Powered ATS Resume Builder</title>
    <meta name="description" content="Build recruiter-approved, ATS-optimized resumes in minutes. Beat the bots, land more interviews.">
    <link rel="icon" type="image/png" href="/favicon.png">
    @vite(['resources/css/app.css'])
    <style>
        /* ── LANDING-ONLY STYLES ── */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        /* Noise texture overlay */
        body::after {
            content: '';
            position: fixed; inset: 0; z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.035'/%3E%3C/svg%3E");
            pointer-events: none;
            opacity: 0.6;
        }

        /* ── TOP NAV ── */
        .lp-nav {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 200;
            padding: 0 48px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.4s ease, border-color 0.4s ease;
        }
        .lp-nav.scrolled {
            background: rgba(4,6,15,0.8);
            backdrop-filter: blur(28px) saturate(180%);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 1px 0 rgba(255,255,255,0.03);
        }

        .lp-logo {
            font-family: 'Outfit', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #f0f4ff;
        }
        .lp-logo-icon {
            width: 38px; height: 38px;
            border-radius: 11px;
            background: linear-gradient(135deg, #7c3aed 0%, #06d6a0 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 0 22px rgba(124,58,237,0.6), inset 0 1px 0 rgba(255,255,255,0.2);
            flex-shrink: 0;
        }

        .lp-nav-actions { display: flex; align-items: center; gap: 10px; }

        .btn-nav-secondary {
            padding: 9px 20px;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: rgba(240,244,255,0.75);
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
            transition: all 0.25s ease;
            letter-spacing: -0.01em;
        }
        .btn-nav-secondary:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.15);
            color: #f0f4ff;
        }

        .btn-nav-primary {
            padding: 9px 22px;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #7c3aed 0%, #06d6a0 100%);
            border: none;
            text-decoration: none;
            transition: all 0.25s ease;
            letter-spacing: -0.01em;
            box-shadow: 0 2px 14px rgba(124,58,237,0.4);
        }
        .btn-nav-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(6,214,160,0.4);
            color: white;
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 32px 80px;
            position: relative;
            overflow: hidden;
        }

        /* Hero glow orbs */
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }
        .hero-orb-1 {
            width: 700px; height: 700px;
            top: -200px; left: 50%;
            transform: translateX(-50%);
            background: radial-gradient(circle, rgba(139,92,246,0.22) 0%, transparent 65%);
            animation: orbPulse 8s ease-in-out infinite alternate;
        }
        .hero-orb-2 {
            width: 500px; height: 500px;
            bottom: 0; left: -100px;
            background: radial-gradient(circle, rgba(6,214,160,0.15) 0%, transparent 65%);
            animation: orbPulse 10s 2s ease-in-out infinite alternate;
        }
        .hero-orb-3 {
            width: 400px; height: 400px;
            bottom: -100px; right: -80px;
            background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 65%);
            animation: orbPulse 12s 4s ease-in-out infinite alternate;
        }
        @keyframes orbPulse {
            0%   { transform: translateX(-50%) scale(0.9); opacity: 0.7; }
            100% { transform: translateX(-50%) scale(1.15); opacity: 1; }
        }
        .hero-orb-2, .hero-orb-3 {
            animation: orbPulse2 10s ease-in-out infinite alternate;
        }
        @keyframes orbPulse2 {
            0%   { transform: scale(0.9); opacity: 0.6; }
            100% { transform: scale(1.2); opacity: 1; }
        }

        /* Hero content */
        .hero-content {
            position: relative; z-index: 2;
            max-width: 860px;
        }

        /* Pill badge */
        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 100px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(139,92,246,0.3);
            color: rgba(240,244,255,0.85);
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 2.2rem;
            backdrop-filter: blur(12px);
            box-shadow: 0 0 24px rgba(139,92,246,0.15);
            animation: floatPill 5s ease-in-out infinite;
        }
        .hero-pill-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #06d6a0;
            box-shadow: 0 0 8px #06d6a0;
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes floatPill {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
        @keyframes blink {
            0%,100% { opacity: 1; }
            50%      { opacity: 0.4; }
        }

        /* Hero headline */
        .hero-h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(3.2rem, 7vw, 5.5rem);
            font-weight: 900;
            line-height: 1.0;
            letter-spacing: -0.045em;
            margin-bottom: 1.6rem;
            color: #f0f4ff;
        }
        .hero-h1 .grad {
            background: linear-gradient(135deg, #c4b5fd 0%, #06d6a0 55%, #34d9b0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 200%;
            animation: gradientShift 4s ease infinite;
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero-sub {
            font-size: clamp(1rem, 2.2vw, 1.2rem);
            color: rgba(122,136,168,1);
            max-width: 600px;
            margin: 0 auto 3rem;
            line-height: 1.85;
            font-weight: 400;
        }

        /* CTA buttons */
        .hero-ctas {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 3.5rem;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 17px 40px;
            border-radius: 100px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: white;
            background: linear-gradient(135deg, #7c3aed 0%, #06d6a0 100%);
            border: none;
            text-decoration: none;
            transition: all 0.35s ease;
            box-shadow:
                0 4px 24px rgba(124,58,237,0.5),
                0 0 0 1px rgba(255,255,255,0.1) inset;
            position: relative;
            overflow: hidden;
        }
        .btn-hero-primary::before {
            content: '';
            position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: skewX(-20deg);
            transition: left 0.7s ease;
        }
        .btn-hero-primary:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 10px 40px rgba(6,214,160,0.5); color: white; }
        .btn-hero-primary:hover::before { left: 160%; }

        .btn-hero-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 30px;
            border-radius: 100px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            color: rgba(240,244,255,0.7);
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .btn-hero-ghost:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.18);
            color: #f0f4ff;
            transform: translateY(-1px);
        }

        /* Social proof */
        .hero-social-proof {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        .proof-avatars {
            display: flex;
            align-items: center;
        }
        .proof-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            border: 2px solid rgba(4,6,15,0.9);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: -10px;
        }
        .proof-avatar:first-child { margin-left: 0; }
        .pa1 { background: linear-gradient(135deg, #7c3aed, #a855f7); }
        .pa2 { background: linear-gradient(135deg, #06d6a0, #0891b2); }
        .pa3 { background: linear-gradient(135deg, #ec4899, #f43f5e); }
        .pa4 { background: linear-gradient(135deg, #f59e0b, #ef4444); }
        .pa5 { background: linear-gradient(135deg, #8b5cf6, #06d6a0); }
        .proof-text {
            font-size: 0.85rem;
            color: rgba(122,136,168,0.9);
            font-weight: 500;
        }
        .proof-stars {
            color: #f59e0b;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        .proof-sep {
            width: 4px; height: 4px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
        }

        /* ── APP MOCKUP ── */
        .mockup-wrap {
            position: relative;
            z-index: 2;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 32px;
        }
        .mockup-frame {
            position: relative;
            border-radius: 20px 20px 0 0;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
            border-bottom: none;
            background: linear-gradient(160deg,
                rgba(13,17,32,0.98) 0%,
                rgba(8,12,26,0.98) 100%
            );
            box-shadow:
                0 -30px 80px rgba(139,92,246,0.12),
                0 40px 80px rgba(0,0,0,0.7),
                inset 0 1px 0 rgba(255,255,255,0.08);
            mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
        }
        /* Window chrome bar */
        .mockup-chrome {
            height: 44px;
            background: rgba(0,0,0,0.4);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            padding: 0 18px;
            gap: 8px;
        }
        .chrome-dot {
            width: 12px; height: 12px;
            border-radius: 50%;
        }
        .chrome-dot-r { background: #ef4444; }
        .chrome-dot-y { background: #f59e0b; }
        .chrome-dot-g { background: #10b981; }
        .chrome-url {
            flex: 1;
            height: 24px;
            background: rgba(255,255,255,0.04);
            border-radius: 6px;
            margin: 0 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            color: rgba(255,255,255,0.3);
            font-family: 'Inter', monospace;
        }

        .mockup-body {
            display: grid;
            grid-template-columns: 240px 1fr 200px;
            gap: 0;
            height: 420px;
            overflow: hidden;
        }

        /* Sidebar mockup */
        .mock-sidebar {
            background: rgba(4,6,15,0.9);
            border-right: 1px solid rgba(255,255,255,0.04);
            padding: 20px 16px;
        }
        .mock-logo {
            height: 28px;
            background: linear-gradient(90deg, rgba(139,92,246,0.6), rgba(6,214,160,0.4));
            border-radius: 6px;
            margin-bottom: 24px;
        }
        .mock-nav-item {
            height: 32px;
            border-radius: 6px;
            margin-bottom: 6px;
            background: rgba(255,255,255,0.03);
        }
        .mock-nav-item.active {
            background: linear-gradient(90deg, rgba(124,58,237,0.2), rgba(6,214,160,0.08));
            border: 1px solid rgba(124,58,237,0.2);
        }

        /* Form panel */
        .mock-form {
            background: rgba(8,12,26,0.8);
            padding: 20px;
            overflow: hidden;
        }
        .mock-section-title {
            height: 14px; width: 100px;
            background: rgba(255,255,255,0.08);
            border-radius: 4px;
            margin-bottom: 16px;
        }
        .mock-field {
            height: 38px;
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .mock-field.active {
            border-color: rgba(6,214,160,0.4);
            box-shadow: 0 0 0 3px rgba(6,214,160,0.08);
        }
        .mock-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
        .mock-btn {
            height: 36px;
            background: linear-gradient(135deg, rgba(124,58,237,0.6), rgba(6,214,160,0.4));
            border-radius: 8px;
            margin-top: 16px;
        }

        /* Resume preview panel */
        .mock-preview {
            background: #e8e9ed;
            position: relative;
            overflow: hidden;
        }
        .mock-paper-header {
            background: #2d2d3d;
            height: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 10px 16px;
        }
        .mock-name-line {
            height: 12px; width: 110px;
            background: rgba(255,255,255,0.7);
            border-radius: 3px;
            margin-bottom: 6px;
        }
        .mock-title-line {
            height: 8px; width: 80px;
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        .mock-paper-body { padding: 12px; }
        .mock-section-bar {
            height: 8px; width: 70px;
            background: #2d2d3d;
            border-radius: 3px;
            margin-bottom: 8px;
        }
        .mock-line {
            height: 6px;
            background: #cdd0d9;
            border-radius: 3px;
            margin-bottom: 5px;
        }
        .mock-line.w80  { width: 80%; }
        .mock-line.w60  { width: 60%; }
        .mock-line.w90  { width: 90%; }
        .mock-line.w70  { width: 70%; }
        .mock-divider {
            height: 1px;
            background: #c5c9d4;
            margin: 10px 0;
        }

        /* ATS Score floater on mockup */
        .mock-score-badge {
            position: absolute;
            top: 30px; right: -20px;
            background: linear-gradient(135deg, rgba(13,17,32,0.95), rgba(8,12,26,0.95));
            border: 1px solid rgba(6,214,160,0.3);
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 0 30px rgba(6,214,160,0.2), var(--shadow-md);
            backdrop-filter: blur(12px);
            text-align: center;
            animation: floatBadge 4s ease-in-out infinite;
            z-index: 5;
        }
        @keyframes floatBadge {
            0%,100% { transform: translateY(0) rotate(2deg); }
            50%      { transform: translateY(-8px) rotate(2deg); }
        }
        .mock-score-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #06d6a0;
            line-height: 1;
        }
        .mock-score-lbl {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(122,136,168,0.9);
            margin-top: 3px;
        }

        /* Keyword match floater */
        .mock-kw-badge {
            position: absolute;
            bottom: 40px; left: -20px;
            background: linear-gradient(135deg, rgba(13,17,32,0.95), rgba(8,12,26,0.95));
            border: 1px solid rgba(139,92,246,0.3);
            border-radius: 12px;
            padding: 10px 14px;
            box-shadow: 0 0 24px rgba(139,92,246,0.2), var(--shadow-md);
            animation: floatBadge2 5s ease-in-out infinite;
            z-index: 5;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        @keyframes floatBadge2 {
            0%,100% { transform: translateY(0) rotate(-1deg); }
            50%      { transform: translateY(-6px) rotate(-1deg); }
        }
        .mock-kw-icon { color: #a78bfa; font-size: 1rem; }
        .mock-kw-text { font-size: 0.75rem; font-weight: 700; color: #c4b5fd; }
        .mock-kw-sub { font-size: 0.65rem; color: rgba(122,136,168,0.8); }

        /* ── FEATURES SECTION ── */
        .section {
            padding: 100px 32px;
            position: relative;
        }
        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 100px;
            background: rgba(139,92,246,0.08);
            border: 1px solid rgba(139,92,246,0.2);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #a78bfa;
            margin-bottom: 20px;
        }
        .section-h2 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1.1;
            margin-bottom: 16px;
            color: #f0f4ff;
        }
        .section-sub {
            font-size: 1.05rem;
            color: rgba(122,136,168,0.9);
            line-height: 1.75;
            max-width: 560px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 64px;
        }

        .feature-card {
            padding: 32px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.06);
            background: linear-gradient(145deg,
                rgba(255,255,255,0.035) 0%,
                rgba(255,255,255,0.01) 100%
            );
            backdrop-filter: blur(16px);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--fc-color, #a78bfa), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .feature-card:hover { transform: translateY(-6px); border-color: rgba(255,255,255,0.1); box-shadow: 0 24px 60px rgba(0,0,0,0.5); }
        .feature-card:hover::before { opacity: 1; }

        .feature-icon-wrap {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
            position: relative;
        }
        .feature-icon-wrap::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 18px;
            background: inherit;
            opacity: 0.15;
            filter: blur(8px);
        }
        .fi-violet { background: linear-gradient(135deg, rgba(139,92,246,0.3), rgba(124,58,237,0.15)); border: 1px solid rgba(139,92,246,0.2); --fc-color: #a78bfa; }
        .fi-cyan   { background: linear-gradient(135deg, rgba(6,214,160,0.25), rgba(6,182,212,0.12)); border: 1px solid rgba(6,214,160,0.2); --fc-color: #06d6a0; }
        .fi-gold   { background: linear-gradient(135deg, rgba(245,158,11,0.25), rgba(234,179,8,0.12)); border: 1px solid rgba(245,158,11,0.2); --fc-color: #f59e0b; }
        .fi-blue   { background: linear-gradient(135deg, rgba(59,130,246,0.25), rgba(99,102,241,0.12)); border: 1px solid rgba(59,130,246,0.2); --fc-color: #60a5fa; }
        .fi-pink   { background: linear-gradient(135deg, rgba(236,72,153,0.25), rgba(244,63,94,0.12)); border: 1px solid rgba(236,72,153,0.2); --fc-color: #f472b6; }
        .fi-emerald{ background: linear-gradient(135deg, rgba(16,185,129,0.25), rgba(20,184,166,0.12)); border: 1px solid rgba(16,185,129,0.2); --fc-color: #34d399; }

        .feature-h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 10px;
            color: #f0f4ff;
        }
        .feature-p {
            font-size: 0.9rem;
            color: rgba(122,136,168,0.9);
            line-height: 1.7;
        }

        /* ── STATS BAND ── */
        .stats-band {
            padding: 70px 32px;
            border-top: 1px solid rgba(255,255,255,0.04);
            border-bottom: 1px solid rgba(255,255,255,0.04);
            background: rgba(8,12,26,0.6);
            backdrop-filter: blur(12px);
        }
        .stats-row {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
        }
        .stat-item {
            text-align: center;
            padding: 0 24px;
            position: relative;
        }
        .stat-item + .stat-item::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 1px; height: 50%;
            background: rgba(255,255,255,0.06);
        }
        .stat-num {
            font-family: 'Outfit', sans-serif;
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: -0.05em;
            line-height: 1;
            margin-bottom: 8px;
        }
        .stat-desc {
            font-size: 0.85rem;
            color: rgba(122,136,168,0.8);
            font-weight: 500;
        }

        /* ── HOW IT WORKS ── */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            margin-top: 64px;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }
        .step-card {
            position: relative;
            padding: 36px 28px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.06);
            background: linear-gradient(145deg,
                rgba(255,255,255,0.03) 0%,
                rgba(255,255,255,0.008) 100%
            );
            text-align: center;
        }
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px; height: 52px;
            border-radius: 50%;
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
        }
        .step-number::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: conic-gradient(var(--sn-color, #a78bfa) var(--sn-pct, 33%), transparent 0%);
            opacity: 0.5;
            animation: spin-slow 8s linear infinite;
        }
        .sn-1 { background: rgba(139,92,246,0.15); color: #a78bfa; --sn-color: #a78bfa; --sn-pct: 33%; }
        .sn-2 { background: rgba(6,214,160,0.12);  color: #06d6a0; --sn-color: #06d6a0; --sn-pct: 66%; }
        .sn-3 { background: rgba(245,158,11,0.12); color: #f59e0b; --sn-color: #f59e0b; --sn-pct: 100%; }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        .step-h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #f0f4ff;
        }
        .step-p { font-size: 0.88rem; color: rgba(122,136,168,0.9); line-height: 1.7; }

        /* Step connector */
        .steps-grid-wrap {
            position: relative;
        }

        /* ── FINAL CTA ── */
        .cta-section {
            padding: 120px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 800px; height: 400px;
            background: radial-gradient(ellipse, rgba(139,92,246,0.14) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-card {
            position: relative;
            z-index: 1;
            max-width: 720px;
            margin: 0 auto;
            padding: 64px;
            border-radius: 28px;
            border: 1px solid rgba(139,92,246,0.2);
            background: linear-gradient(145deg,
                rgba(255,255,255,0.04) 0%,
                rgba(255,255,255,0.015) 100%
            );
            backdrop-filter: blur(24px);
            box-shadow:
                0 0 0 1px rgba(139,92,246,0.08) inset,
                0 40px 80px rgba(0,0,0,0.5),
                0 0 80px rgba(139,92,246,0.12);
        }
        .cta-h2 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            margin-bottom: 14px;
        }
        .cta-p {
            font-size: 1.05rem;
            color: rgba(122,136,168,0.9);
            max-width: 480px;
            margin: 0 auto 36px;
            line-height: 1.75;
        }
        .cta-note {
            margin-top: 20px;
            font-size: 0.8rem;
            color: rgba(122,136,168,0.6);
        }

        /* ── FOOTER ── */
        .lp-footer {
            padding: 32px;
            border-top: 1px solid rgba(255,255,255,0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .footer-brand {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: rgba(240,244,255,0.8);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .footer-copy {
            font-size: 0.82rem;
            color: rgba(122,136,168,0.6);
        }
        .footer-links { display: flex; gap: 20px; }
        .footer-link {
            font-size: 0.82rem;
            color: rgba(122,136,168,0.6);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .footer-link:hover { color: rgba(240,244,255,0.8); }
    </style>
</head>
<body>

    <!-- NAV -->
    <nav class="lp-nav" id="lp-nav">
        <a href="/" class="lp-logo">
            <div class="lp-logo-icon">⚡</div>
            ResumeForge
        </a>
        <div class="lp-nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-nav-primary">Dashboard →</a>
            @else
                <a href="{{ route('login') }}" class="btn-nav-secondary">Sign In</a>
                <a href="{{ route('register') }}" class="btn-nav-primary">Get Started Free</a>
            @endauth
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>

        <div class="hero-content">
            <div class="hero-pill" style="animation-delay: 0s">
                <span class="hero-pill-dot"></span>
                AI-Powered ATS Optimization Engine — Now Live
            </div>

            <h1 class="hero-h1">
                The <span class="grad">unfair advantage</span><br>
                for your next job.
            </h1>

            <p class="hero-sub">
                Build stunning, ATS-optimized resumes in minutes. Our keyword-matching engine scores your resume against any job description and tells you exactly how to get past the bots.
            </p>

            <div class="hero-ctas">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                        <span>⚡</span> Enter Workspace
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        <span>⚡</span> Build Your Resume Free
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero-ghost">
                        Sign In →
                    </a>
                @endauth
            </div>


        </div>
    </section>

    <!-- APP MOCKUP -->
    <div class="mockup-wrap">
        <div class="mockup-frame" style="position:relative;">
            <!-- Window Chrome -->
            <div class="mockup-chrome">
                <div class="chrome-dot chrome-dot-r"></div>
                <div class="chrome-dot chrome-dot-y"></div>
                <div class="chrome-dot chrome-dot-g"></div>
                <div class="chrome-url">app.resumeforge.io/resumes/builder</div>
            </div>
            <!-- App Body -->
            <div class="mockup-body">
                <!-- Sidebar -->
                <div class="mock-sidebar">
                    <div class="mock-logo"></div>
                    <div class="mock-nav-item active"></div>
                    <div class="mock-nav-item"></div>
                    <div class="mock-nav-item"></div>
                    <div class="mock-nav-item"></div>
                    <div style="margin-top:16px; height:1px; background: rgba(255,255,255,0.04); margin-bottom:16px;"></div>
                    <div class="mock-nav-item"></div>
                    <div class="mock-nav-item"></div>
                </div>
                <!-- Form Panel -->
                <div class="mock-form">
                    <div style="display:flex;gap:8px;margin-bottom:20px;">
                        <div style="height:26px;padding:0 12px;border-radius:100px;background:linear-gradient(135deg,rgba(124,58,237,0.5),rgba(6,214,160,0.3));display:flex;align-items:center;">
                            <div style="height:8px;width:50px;background:rgba(255,255,255,0.5);border-radius:4px;"></div>
                        </div>
                        <div style="height:26px;padding:0 12px;border-radius:100px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;">
                            <div style="height:8px;width:40px;background:rgba(255,255,255,0.15);border-radius:4px;"></div>
                        </div>
                        <div style="height:26px;padding:0 12px;border-radius:100px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;">
                            <div style="height:8px;width:35px;background:rgba(255,255,255,0.15);border-radius:4px;"></div>
                        </div>
                    </div>
                    <div class="mock-section-title"></div>
                    <div class="mock-field active"></div>
                    <div class="mock-field-row">
                        <div class="mock-field"></div>
                        <div class="mock-field"></div>
                    </div>
                    <div class="mock-field"></div>
                    <div class="mock-field"></div>
                    <div style="height:70px;background:rgba(0,0,0,0.25);border:1px solid rgba(255,255,255,0.06);border-radius:8px;margin-bottom:10px;"></div>
                    <div class="mock-btn"></div>
                </div>
                <!-- Resume Preview -->
                <div class="mock-preview" style="position:relative;">
                    <div class="mock-paper-header">
                        <div class="mock-name-line"></div>
                        <div class="mock-title-line"></div>
                    </div>
                    <div class="mock-paper-body">
                        <div class="mock-section-bar"></div>
                        <div class="mock-line w90"></div>
                        <div class="mock-line w80"></div>
                        <div class="mock-line w70"></div>
                        <div class="mock-divider"></div>
                        <div class="mock-section-bar"></div>
                        <div class="mock-line w80"></div>
                        <div class="mock-line w60"></div>
                        <div class="mock-divider"></div>
                        <div class="mock-section-bar"></div>
                        <div class="mock-line w90"></div>
                        <div class="mock-line w80"></div>
                    </div>

                    <!-- ATS Score badge -->
                    <div class="mock-score-badge">
                        <div class="mock-score-val">92%</div>
                        <div class="mock-score-lbl">ATS Score</div>
                    </div>

                    <!-- Keywords badge -->
                    <div class="mock-kw-badge">
                        <div class="mock-kw-icon">🎯</div>
                        <div>
                            <div class="mock-kw-text">18/20 Keywords</div>
                            <div class="mock-kw-sub">Matched</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS BAND -->
    <div class="stats-band" style="margin-top: -1px;">
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-num" style="background:linear-gradient(135deg,#a78bfa,#06d6a0);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">10K+</div>
                <div class="stat-desc">Resumes Created</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" style="background:linear-gradient(135deg,#06d6a0,#60a5fa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">92%</div>
                <div class="stat-desc">ATS Pass Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" style="background:linear-gradient(135deg,#f59e0b,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">3×</div>
                <div class="stat-desc">More Interviews</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" style="background:linear-gradient(135deg,#60a5fa,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Free</div>
                <div class="stat-desc">Forever — No CC</div>
            </div>
        </div>
    </div>

    <!-- FEATURES -->
    <section class="section" style="max-width: 1300px; margin: 0 auto;">
        <div style="text-align:center; max-width: 600px; margin: 0 auto 0;">
            <div class="section-tag">✦ Features</div>
            <h2 class="section-h2">Everything you need<br>to land the interview</h2>
            <p class="section-sub" style="margin: 0 auto;">From building to scoring to exporting — every tool you need to craft a perfect, ATS-beating resume.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card" style="--fc-color: #a78bfa;">
                <div class="feature-icon-wrap fi-violet">🤖</div>
                <h3 class="feature-h3">AI ATS Scoring</h3>
                <p class="feature-p">Paste any job description and get an instant ATS score. See exactly which keywords you're missing and how to fix it.</p>
            </div>
            <div class="feature-card" style="--fc-color: #06d6a0;">
                <div class="feature-icon-wrap fi-cyan">⚡</div>
                <h3 class="feature-h3">Live Preview</h3>
                <p class="feature-p">See your resume update in real-time as you type. What you see is exactly what gets exported as a pixel-perfect PDF.</p>
            </div>
            <div class="feature-card" style="--fc-color: #f59e0b;">
                <div class="feature-icon-wrap fi-gold">📄</div>
                <h3 class="feature-h3">One-Click PDF Export</h3>
                <p class="feature-p">Export a beautifully formatted, ATS-readable PDF in one click. Clean HTML structure that every parser can read.</p>
            </div>
            <div class="feature-card" style="--fc-color: #60a5fa;">
                <div class="feature-icon-wrap fi-blue">🗂️</div>
                <h3 class="feature-h3">Multi-Version Management</h3>
                <p class="feature-p">Maintain tailored resume versions for different roles. Organize and switch between them instantly from your dashboard.</p>
            </div>
            <div class="feature-card" style="--fc-color: #f472b6;">
                <div class="feature-icon-wrap fi-pink">🎨</div>
                <h3 class="feature-h3">Premium Templates</h3>
                <p class="feature-p">Choose from professionally designed templates that are both visually stunning and 100% ATS-compatible.</p>
            </div>
            <div class="feature-card" style="--fc-color: #34d399;">
                <div class="feature-icon-wrap fi-emerald">📊</div>
                <h3 class="feature-h3">Keyword Analytics</h3>
                <p class="feature-p">Detailed keyword gap analysis comparing your resume to job postings. Know exactly what recruiters and bots are looking for.</p>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="section" style="background: rgba(8,12,26,0.5); border-top: 1px solid rgba(255,255,255,0.04);">
        <div style="text-align:center; max-width: 600px; margin: 0 auto;">
            <div class="section-tag">✦ Process</div>
            <h2 class="section-h2">Go from zero to hired<br>in 3 steps</h2>
        </div>

        <div class="steps-grid steps-grid-wrap">
            <div class="step-card">
                <div class="step-number sn-1">1</div>
                <h3 class="step-h3">Build Your Resume</h3>
                <p class="step-p">Fill in your details using our intuitive form. Add work experience, education, skills, and projects — all in one place.</p>
            </div>
            <div class="step-card">
                <div class="step-number sn-2">2</div>
                <h3 class="step-h3">Score Against Job Posts</h3>
                <p class="step-p">Paste a job description and instantly see your ATS match score. Get specific feedback on what to add or change.</p>
            </div>
            <div class="step-card">
                <div class="step-number sn-3">3</div>
                <h3 class="step-h3">Export & Apply</h3>
                <p class="step-p">Download your polished PDF resume and apply with confidence knowing you've optimized for both humans and ATS bots.</p>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="cta-section">
        <div class="cta-card">
            <div class="hero-pill" style="margin-bottom: 24px; display: inline-flex;">
                <span class="hero-pill-dot"></span>
                100% Free — No Credit Card Required
            </div>
            <h2 class="cta-h2">Ready to land your<br><span style="background:linear-gradient(135deg,#c4b5fd,#06d6a0);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">dream job?</span></h2>
            <p class="cta-p">Join thousands of job seekers who have used ResumeForge to beat ATS systems and land more interviews.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-hero-primary" style="font-size:1.05rem; padding:17px 44px;">
                    <span>⚡</span> Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-hero-primary" style="font-size:1.05rem; padding:17px 44px;">
                    <span>⚡</span> Create Free Account
                </a>
            @endauth
            <p class="cta-note">No credit card • Takes 2 minutes • ATS results in seconds</p>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="lp-footer">
        <a href="/" class="footer-brand">
            <div class="lp-logo-icon" style="width:28px;height:28px;font-size:0.85rem;">⚡</div>
            ResumeForge
        </a>
        <p class="footer-copy">© {{ date('Y') }} ResumeForge. Built for job seekers.</p>
        <div class="footer-links">
            <a href="{{ route('login') }}" class="footer-link">Sign In</a>
            <a href="{{ route('register') }}" class="footer-link">Register</a>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const nav = document.getElementById('lp-nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });

        // Intersection observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.feature-card, .step-card, .stat-item').forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(28px)';
            el.style.transition = `opacity 0.6s ease ${i * 0.08}s, transform 0.6s cubic-bezier(0.16,1,0.3,1) ${i * 0.08}s`;
            observer.observe(el);
        });
    </script>
</body>
</html>
