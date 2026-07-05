@extends('layouts.app')

@section('content')
<div class="container mt-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold">Dashboard</h1>
            <p class="text-muted">Manage your resumes and ATS scores</p>
        </div>
        <a href="{{ route('resumes.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Create New Resume
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="glass-card">
            <h3 class="text-muted text-sm uppercase tracking-wider mb-2">Total Resumes</h3>
            <p class="text-4xl font-bold text-gradient">{{ $resumes->count() }}</p>
        </div>
        <div class="glass-card">
            <h3 class="text-muted text-sm uppercase tracking-wider mb-2">ATS Tested</h3>
            <p class="text-4xl font-bold text-gradient">{{ $totalScored }}</p>
        </div>
        <div class="glass-card">
            <h3 class="text-muted text-sm uppercase tracking-wider mb-2">Avg. ATS Score</h3>
            <p class="text-4xl font-bold {{ $avgScore >= 80 ? 'text-green-400' : ($avgScore >= 60 ? 'text-yellow-400' : 'text-red-400') }}">
                {{ $avgScore !== null ? $avgScore . '%' : 'N/A' }}
            </p>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-4">Your Resumes</h2>
    
    @if($resumes->isEmpty())
        <div class="glass-card text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto text-muted mb-4" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            <h3 class="text-lg font-medium mb-2">No resumes yet</h3>
            <p class="text-muted mb-4">Create your first ATS-optimized resume to get started.</p>
            <a href="{{ route('resumes.create') }}" class="btn btn-primary">Create Resume</a>
        </div>
    @else
        <div class="grid grid-cols-2 gap-6">
            @foreach($resumes as $resume)
                <div class="glass-card relative border {{ $resume->is_primary ? 'border-violet-500' : '' }}">
                    @if($resume->is_primary)
                        <span class="badge badge-primary absolute top-4 right-4">Primary</span>
                    @endif
                    
                    <h3 class="text-xl font-bold mb-1">{{ $resume->title }}</h3>
                    <p class="text-sm text-muted mb-4">Updated {{ $resume->updated_at->diffForHumans() }}</p>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="text-center px-4 py-2 rounded bg-black bg-opacity-30">
                            <div class="text-xs text-muted mb-1">ATS Score</div>
                            @if($resume->latestAtsScore)
                                <div class="font-bold text-lg" style="color: {{ $resume->latestAtsScore->score_color }}">
                                    {{ $resume->latestAtsScore->overall_score }}%
                                </div>
                            @else
                                <div class="font-bold text-lg text-muted">--</div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <div class="text-xs text-muted mb-1">Sections Filled</div>
                            <div class="flex gap-2 text-sm">
                                <span class="{{ $resume->experiences_count > 0 ? 'text-green-400' : 'text-muted' }}">Exp: {{ $resume->experiences_count }}</span>
                                <span class="{{ $resume->education_count > 0 ? 'text-green-400' : 'text-muted' }}">Edu: {{ $resume->education_count }}</span>
                                <span class="{{ $resume->skills_count > 0 ? 'text-green-400' : 'text-muted' }}">Skills: {{ $resume->skills_count }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('resumes.builder', $resume) }}" class="btn btn-primary flex-1">Edit</a>
                        <a href="{{ route('resumes.ats.history', $resume) }}" class="btn btn-secondary">ATS</a>
                        <form action="{{ route('resumes.destroy', $resume) }}" method="POST" class="inline" onsubmit="return confirm('Delete this resume?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
