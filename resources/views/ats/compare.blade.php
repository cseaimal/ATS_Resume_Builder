@extends('layouts.app')

@section('content')
<div class="container mt-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold">Cross-Version ATS Comparison</h1>
            <p class="text-muted">See how your resume versions rank against each other.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if(empty($comparison))
        <div class="glass-card text-center py-12">
            <h3 class="text-lg font-bold mb-2">No data to compare yet</h3>
            <p class="text-muted mb-6">Create multiple resumes and run ATS tests to compare them here.</p>
            <a href="{{ route('resumes.create') }}" class="btn btn-primary">Create Resume</a>
        </div>
    @else
        <div class="glass-card" style="padding: 0; overflow: hidden;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:rgba(0,0,0,0.3);border-bottom:1px solid rgba(255,255,255,0.08);">
                        <th style="padding:16px;text-align:left;font-weight:600;color:var(--text-secondary);font-size:0.875rem;">#</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:var(--text-secondary);font-size:0.875rem;">Resume Version</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:var(--text-secondary);font-size:0.875rem;">Status</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:var(--text-secondary);font-size:0.875rem;">ATS Score</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:var(--text-secondary);font-size:0.875rem;">Last Tested</th>
                        <th style="padding:16px;text-align:left;font-weight:600;color:var(--text-secondary);font-size:0.875rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comparison as $index => $row)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.05);{{ $index === 0 && $row['score'] !== null ? 'background:rgba(124,58,237,0.05);' : '' }}transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='{{ $index === 0 && $row['score'] !== null ? 'rgba(124,58,237,0.05)' : 'transparent' }}'">
                            <td style="padding:16px;color:var(--text-secondary);font-size:0.875rem;">{{ $index + 1 }}</td>
                            <td style="padding:16px;">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-white">{{ $row['resume_title'] }}</span>
                                    @if($index === 0 && $row['score'] !== null)
                                        <span style="font-size:0.7rem;background:rgba(251,191,36,0.15);color:#fbbf24;padding:2px 8px;border-radius:4px;text-transform:uppercase;letter-spacing:0.5px;">Top Score</span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding:16px;">
                                @if($row['is_primary'])
                                    <span class="badge badge-primary">Primary</span>
                                @else
                                    <span class="text-muted text-sm">Secondary</span>
                                @endif
                            </td>
                            <td style="padding:16px;">
                                @if($row['score'] !== null)
                                    <div class="flex items-center gap-2">
                                        <div style="font-size:1.5rem;font-weight:800;color:{{ $row['color'] }};">{{ $row['score'] }}%</div>
                                        <div class="text-xs text-muted">{{ $row['label'] }}</div>
                                    </div>
                                @else
                                    <span class="text-muted">Not tested yet</span>
                                @endif
                            </td>
                            <td style="padding:16px;" class="text-sm text-muted">{{ $row['scored_at'] }}</td>
                            <td style="padding:16px;">
                                <div class="flex gap-2">
                                    <a href="{{ route('resumes.builder', $row['resume_id']) }}" class="btn btn-secondary" style="font-size:0.8rem;padding:6px 12px;">Edit</a>
                                    <a href="{{ route('resumes.ats.history', $row['resume_id']) }}" class="btn btn-secondary" style="font-size:0.8rem;padding:6px 12px;">ATS History</a>
                                    @if(!$row['is_primary'])
                                        <form action="{{ route('resumes.set-primary', $row['resume_id']) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" style="font-size:0.8rem;padding:6px 12px;">Set Primary</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
