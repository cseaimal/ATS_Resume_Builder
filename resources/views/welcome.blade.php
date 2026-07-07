<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ResumeForge') }} | Premium ATS Resume Builder</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    @vite(['resources/css/app.css'])
    <style>
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
            position: relative;
            overflow: hidden;
        }
        .glow-orb {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(20,184,166,0.15) 0%, rgba(139,92,246,0) 70%);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            animation: pulse 8s infinite alternate;
        }
        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            100% { transform: translate(-50%, -50%) scale(1.3); opacity: 1; }
        }
        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 800px;
        }
        .hero-title {
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.05;
            letter-spacing: -0.03em;
        }
        .hero-subtitle {
            font-size: clamp(1.1rem, 2vw, 1.25rem);
            color: var(--text-secondary);
            max-width: 650px;
            margin: 0 auto 3rem;
            line-height: 1.8;
        }
        .floating-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 100px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            animation: float 6s ease-in-out infinite;
        }
        .floating-badge span {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #14b8a6;
            border-radius: 50%;
            box-shadow: 0 0 10px #14b8a6;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .mockup-container {
            margin-top: 4rem;
            position: relative;
            perspective: 1000px;
        }
        .mockup {
            width: 100%;
            max-width: 900px;
            height: 400px;
            background: linear-gradient(145deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.01) 100%);
            border-radius: 20px 20px 0 0;
            border: 1px solid rgba(255,255,255,0.1);
            border-bottom: none;
            backdrop-filter: blur(20px);
            margin: 0 auto;
            transform: rotateX(20deg) translateY(50px);
            box-shadow: 0 -20px 60px rgba(20,184,166,0.1), 0 30px 60px rgba(0,0,0,0.5);
            display: flex;
            align-items: flex-start;
            padding: 24px;
            gap: 20px;
            mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        }
        .mockup-sidebar {
            width: 200px;
            height: 100%;
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .mockup-paper {
            flex: 1;
            height: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .nav-top {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 24px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }
        .logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-text img {
            width: 32px;
            height: 32px;
            border-radius: 8px;
        }
    </style>
</head>
<body class="antialiased">
    
    <nav class="nav-top">
        <div class="logo-text">
            <img src="/favicon.png" alt="Logo">
            ResumeForge
        </div>
        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary" style="margin-right: 12px;">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
            @endauth
        </div>
    </nav>

    <div class="hero">
        <div class="glow-orb"></div>
        <div class="hero-content animate-fade-in">
            
            <div class="floating-badge">
                <span></span> AI-Powered ATS Optimization Engine
            </div>

            <h1 class="hero-title">
                The <span class="text-gradient">unfair advantage</span><br>
                for your next career move.
            </h1>
            
            <p class="hero-subtitle">
                Build stunning, recruiter-approved resumes in minutes. Our advanced ATS scoring engine ensures you match the job description perfectly and get past the bots.
            </p>
            
            <div class="flex justify-center gap-6 mt-8">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 16px 40px; font-size: 1.15rem; border-radius: 100px;">Enter Workspace</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 16px 40px; font-size: 1.15rem; border-radius: 100px;">Build Your Resume Free</a>
                @endauth
            </div>

            <div class="mockup-container">
                <div class="mockup">
                    <div class="mockup-sidebar"></div>
                    <div class="mockup-paper"></div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
