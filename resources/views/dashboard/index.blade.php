@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px;">

    {{-- ── PAGE HEADER ── --}}
    <div class="flex justify-between items-center mb-10 animate-fade-in">
        <div>
            <div style="display:inline-flex; align-items:center; gap:8px; padding:5px 12px; border-radius:100px; background:rgba(139,92,246,0.08); border:1px solid rgba(139,92,246,0.18); font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#a78bfa; margin-bottom:14px;">
                ✦ Your Workspace
            </div>
            <h1 style="font-family:'Outfit',sans-serif; font-size:2.4rem; font-weight:800; letter-spacing:-0.04em; margin-bottom:6px;">
                Welcome back 👋
            </h1>
            <p class="text-muted" style="font-size:0.98rem;">Manage your resumes and track your ATS performance.</p>
        </div>
        <a href="{{ route('resumes.create') }}" class="btn btn-primary" style="padding:13px 26px; font-size:0.95rem; border-radius:100px; gap:10px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Resume
        </a>
    </div>

    {{-- ── STATS ROW ── --}}
    <div class="grid grid-cols-3 gap-6 mb-12 animate-fade-in-d1">
        {{-- Total Resumes --}}
        <div class="stat-card violet">
            <div class="stat-icon violet">📄</div>
            <div class="stat-value text-gradient">{{ $resumes->count() }}</div>
            <div class="stat-label">Total Resumes</div>
        </div>

        {{-- ATS Tested --}}
        <div class="stat-card cyan">
            <div class="stat-icon cyan">🎯</div>
            <div class="stat-value" style="background:linear-gradient(135deg,#06d6a0,#34d9b0);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $totalScored }}</div>
            <div class="stat-label">ATS Tested</div>
        </div>

        {{-- Avg Score --}}
        <div class="stat-card gold">
            <div class="stat-icon gold">⚡</div>
            <div class="stat-value"
                style="background:{{ $avgScore >= 80 ? 'linear-gradient(135deg,#10b981,#34d399)' : ($avgScore >= 60 ? 'linear-gradient(135deg,#f59e0b,#fcd34d)' : 'linear-gradient(135deg,#ef4444,#f87171)') }};-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                {{ $avgScore !== null ? $avgScore . '%' : 'N/A' }}
            </div>
            <div class="stat-label">Avg. ATS Score</div>
        </div>
    </div>

    {{-- ── RESUME LIST ── --}}
    <div class="flex justify-between items-center mb-6 animate-fade-in-d2">
        <h2 style="font-family:'Outfit',sans-serif; font-size:1.35rem; font-weight:700; letter-spacing:-0.03em;">
            Your Resumes
        </h2>
        @if($resumes->count() > 0)
            <span class="badge badge-primary">{{ $resumes->count() }} resume{{ $resumes->count() !== 1 ? 's' : '' }}</span>
        @endif
    </div>

    @if($resumes->isEmpty())
        {{-- Empty state --}}
        <div class="glass-card text-center animate-fade-in-d2" style="padding: 72px 32px;">
            <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,rgba(139,92,246,0.15),rgba(6,214,160,0.1));border:1px solid rgba(139,92,246,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:1.8rem;">
                📄
            </div>
            <h3 style="font-family:'Outfit',sans-serif;font-size:1.4rem;font-weight:700;margin-bottom:10px;letter-spacing:-0.03em;">
                No resumes yet
            </h3>
            <p class="text-muted" style="margin-bottom:28px;max-width:360px;margin-left:auto;margin-right:auto;line-height:1.75;">
                Create your first ATS-optimized resume and start landing more interviews today.
            </p>
            <a href="{{ route('resumes.create') }}" class="btn btn-primary" style="padding:13px 28px;border-radius:100px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Create First Resume
            </a>
        </div>

    @else
        <div class="grid grid-cols-2 gap-6 animate-fade-in-d3">
            @foreach($resumes as $resume)
                <div class="resume-card {{ $resume->is_primary ? 'is-primary' : '' }}">

                    {{-- Primary badge --}}
                    @if($resume->is_primary)
                        <div style="position:absolute;top:18px;right:18px;">
                            <span class="badge badge-primary">⭐ Primary</span>
                        </div>
                    @endif

                    {{-- Resume title + date --}}
                    <h3 style="font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:700;letter-spacing:-0.03em;margin-bottom:4px;padding-right:80px;">
                        {{ $resume->title }}
                    </h3>
                    <p style="font-size:0.8rem;color:var(--text-tertiary);margin-bottom:18px;">
                        Updated {{ $resume->updated_at->diffForHumans() }}
                    </p>

                    {{-- Score + sections row --}}
                    <div class="flex items-center gap-3 mb-6" style="flex-wrap:wrap;">
                        {{-- ATS Score pill --}}
                        <div class="resume-score-pill">
                            <span style="font-size:0.75rem;color:var(--text-tertiary);">ATS</span>
                            @if($resume->latestAtsScore)
                                <span style="font-weight:800;font-size:1rem;color:{{ $resume->latestAtsScore->score_color }};">
                                    {{ $resume->latestAtsScore->overall_score }}%
                                </span>
                            @else
                                <span style="font-weight:700;font-size:0.95rem;color:var(--text-tertiary);">—</span>
                            @endif
                        </div>

                        {{-- Section pills --}}
                        <div class="resume-meta-pill {{ $resume->experiences_count > 0 ? 'filled' : '' }}">
                            Exp {{ $resume->experiences_count }}
                        </div>
                        <div class="resume-meta-pill {{ $resume->education_count > 0 ? 'filled' : '' }}">
                            Edu {{ $resume->education_count }}
                        </div>
                        <div class="resume-meta-pill {{ $resume->skills_count > 0 ? 'filled' : '' }}">
                            Skills {{ $resume->skills_count }}
                        </div>
                    </div>

                    {{-- ATS score bar (if scored) --}}
                    @if($resume->latestAtsScore)
                        <div class="progress-track" style="margin-bottom:20px;">
                            <div class="progress-fill" style="width:{{ $resume->latestAtsScore->overall_score }}%;background:linear-gradient(90deg,{{ $resume->latestAtsScore->overall_score >= 80 ? '#10b981,#34d399' : ($resume->latestAtsScore->overall_score >= 60 ? '#f59e0b,#fcd34d' : '#ef4444,#f87171') }});"></div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <a href="{{ route('resumes.builder', $resume) }}" class="btn btn-primary flex-1" style="border-radius:100px; padding:10px 20px; font-size:0.88rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Edit
                        </a>
                        <a href="{{ route('resumes.ats.history', $resume) }}" class="btn btn-secondary" style="border-radius:100px; padding:10px 18px; font-size:0.88rem;" data-tooltip="ATS History">
                            🎯 ATS
                        </a>
                        <form action="{{ route('resumes.destroy', $resume) }}" method="POST"
                              onsubmit="return confirm('Delete this resume?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="border-radius:100px; padding:10px 14px;" data-tooltip="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            {{-- Add new card (when has resumes) --}}
            <a href="{{ route('resumes.create') }}" class="resume-card" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;min-height:180px;border-style:dashed;border-color:rgba(255,255,255,0.07);text-decoration:none;cursor:pointer;transition:all 0.3s ease;" onmouseover="this.style.borderColor='rgba(139,92,246,0.3)';this.style.background='rgba(139,92,246,0.04)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.07)';this.style.background=''">
                <div style="width:50px;height:50px;border-radius:14px;background:rgba(139,92,246,0.1);border:1px solid rgba(139,92,246,0.2);display:flex;align-items:center;justify-content:center;color:#a78bfa;font-size:1.4rem;transition:all 0.3s ease;">+</div>
                <p style="font-size:0.9rem;font-weight:600;color:var(--text-tertiary);margin:0;">Create New Resume</p>
            </a>
        </div>
    @endif

</div>

<style>
    /* Staggered card animations */
    .resume-card, .stat-card {
        opacity: 0;
        transform: translateY(20px);
        animation: cardIn 0.5s var(--ease-out) forwards;
    }
    @for($i = 0; $i < 8; $i++)
        .resume-card:nth-child({{ $i + 1 }}),
        .stat-card:nth-child({{ $i + 1 }}) {
            animation-delay: {{ $i * 0.07 }}s;
        }
    @endfor
    @keyframes cardIn {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
