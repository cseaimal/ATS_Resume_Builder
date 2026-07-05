@extends('layouts.app')

@section('content')
<div class="container mt-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold">My Resumes</h1>
            <p class="text-muted">All your resume versions in one place</p>
        </div>
        <a href="{{ route('resumes.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Resume
        </a>
    </div>

    @if($resumes->isEmpty())
        <div class="glass-card text-center" style="padding: 60px 24px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin: 0 auto 16px; color: var(--text-secondary);" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <h3 class="text-lg font-bold mb-2">No resumes yet</h3>
            <p class="text-muted mb-6">Create your first ATS-optimized resume.</p>
            <a href="{{ route('resumes.create') }}" class="btn btn-primary">Create Resume</a>
        </div>
    @else
        <div class="grid grid-cols-2 gap-6">
            @foreach($resumes as $resume)
                <div class="glass-card relative" style="{{ $resume->is_primary ? 'border-color: var(--accent-violet);' : '' }}">
                    @if($resume->is_primary)
                        <span class="badge badge-primary" style="position: absolute; top: 16px; right: 16px;">Primary</span>
                    @endif

                    <h3 class="text-xl font-bold mb-1">{{ $resume->title }}</h3>
                    <p class="text-sm text-muted mb-4">Updated {{ $resume->updated_at->diffForHumans() }}</p>

                    @if($resume->latestAtsScore)
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-sm text-muted">ATS Score:</span>
                            <span class="font-bold" style="color: {{ $resume->latestAtsScore->score_color }}">
                                {{ $resume->latestAtsScore->overall_score }}% — {{ $resume->latestAtsScore->score_label }}
                            </span>
                        </div>
                    @endif

                    <div class="flex gap-2">
                        <a href="{{ route('resumes.builder', $resume) }}" class="btn btn-primary" style="flex: 1;">Edit</a>
                        @if(!$resume->is_primary)
                            <form action="{{ route('resumes.set-primary', $resume) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Set Primary</button>
                            </form>
                        @endif
                        <a href="{{ route('resumes.ats.history', $resume) }}" class="btn btn-secondary">ATS</a>
                        <form action="{{ route('resumes.destroy', $resume) }}" method="POST" class="inline" onsubmit="return confirm('Delete this resume?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 10px 14px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
