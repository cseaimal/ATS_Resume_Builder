@extends('layouts.app')

@section('content')
<div class="container mt-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold">ATS Score History</h1>
            <p class="text-muted">Resume: <span class="text-white">{{ $resume->title }}</span></p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('resumes.builder', $resume) }}" class="btn btn-secondary">Back to Editor</a>
            <a href="{{ route('dashboard.ats-compare') }}" class="btn btn-primary">Compare All Versions</a>
        </div>
    </div>

    @if($history->isEmpty())
        <div class="glass-card text-center py-12">
            <h3 class="text-lg font-medium mb-2">No ATS tests yet</h3>
            <p class="text-muted mb-4">Go to the Resume Builder and paste a job description to test your score.</p>
            <a href="{{ route('resumes.builder', $resume) }}" class="btn btn-primary">Test ATS Score</a>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($history as $jd)
                <div class="glass-card border-l-4" style="border-left-color: {{ $jd->atsScore->score_color }}">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-white">{{ $jd->jd_title }}</h3>
                            <p class="text-sm text-muted">Tested {{ $jd->atsScore->scored_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-3xl font-bold" style="color: {{ $jd->atsScore->score_color }}">{{ $jd->atsScore->overall_score }}%</div>
                                <div class="text-xs uppercase tracking-wider text-muted">{{ $jd->atsScore->score_label }}</div>
                            </div>
                            <form action="{{ route('resumes.ats.delete-jd', [$resume, $jd]) }}" method="POST" onsubmit="return confirm('Delete this ATS record?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 ml-4 p-2 bg-red-400/10 rounded">Delete</button>
                            </form>
                        </div>
                    </div>

                    @if(!empty($jd->atsScore->flagged_issues))
                        <div class="mb-4">
                            <h4 class="font-bold text-sm text-white mb-2 uppercase tracking-wider">Critical Issues</h4>
                            <div class="grid gap-2">
                                @foreach($jd->atsScore->flagged_issues as $issue)
                                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-3 py-2 rounded text-sm flex gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        {{ $issue['message'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-black/30 p-4 rounded-lg">
                            <h4 class="font-bold text-green-400 mb-2">Matched Keywords</h4>
                            <div class="flex flex-wrap gap-2">
                                @forelse($jd->atsScore->matched_keywords as $kw)
                                    <span class="bg-green-400/10 text-green-400 text-xs px-2 py-1 rounded">{{ $kw }}</span>
                                @empty
                                    <span class="text-muted text-sm">None</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-black/30 p-4 rounded-lg">
                            <h4 class="font-bold text-red-400 mb-2">Missing Keywords</h4>
                            <div class="flex flex-wrap gap-2">
                                @forelse($jd->atsScore->missing_keywords as $kw)
                                    <span class="bg-red-400/10 text-red-400 text-xs px-2 py-1 rounded">{{ $kw }}</span>
                                @empty
                                    <span class="text-muted text-sm">None</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $history->links() }}
        </div>
    @endif
</div>
@endsection
