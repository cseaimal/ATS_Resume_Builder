@extends('layouts.app')

@section('content')
<div style="background: var(--bg-darker); min-height: calc(100vh - 70px); padding: 32px 24px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">{{ $resume->personalInfo->full_name ?? $resume->title }}</h1>
                <p class="text-muted">Resume Preview</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('resumes.builder', $resume) }}" class="btn btn-secondary">← Back to Editor</a>
                <a href="{{ route('resumes.export.pdf', $resume) }}" target="_blank" class="btn btn-primary">Export PDF</a>
            </div>
        </div>

        <div class="a4-paper" style="transform: none; width: 100%;">
            <div style="border-bottom: 2px solid #e5e7eb; padding-bottom: 24px; margin-bottom: 24px; text-align: center;">
                <h1 style="font-size: 2rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 6px; color: #111827;">
                    {{ $resume->personalInfo->full_name ?? '' }}
                </h1>
                <h2 style="font-size: 1.1rem; color: #6b7280; margin-bottom: 12px;">
                    {{ $resume->personalInfo->job_title ?? '' }}
                </h2>
                <div style="display: flex; justify-content: center; gap: 16px; font-size: 0.875rem; color: #9ca3af; flex-wrap: wrap;">
                    @if($resume->personalInfo?->email)
                        <span>{{ $resume->personalInfo->email }}</span>
                    @endif
                    @if($resume->personalInfo?->phone)
                        <span>• {{ $resume->personalInfo->phone }}</span>
                    @endif
                    @if($resume->personalInfo?->location)
                        <span>• {{ $resume->personalInfo->location }}</span>
                    @endif
                    @if($resume->personalInfo?->linkedin)
                        <span>• {{ $resume->personalInfo->linkedin }}</span>
                    @endif
                </div>
            </div>

            @if($resume->personalInfo?->summary)
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Summary</h3>
                    <p style="font-size: 0.875rem; color: #4b5563; line-height: 1.7;">{{ $resume->personalInfo->summary }}</p>
                </div>
            @endif

            @if($resume->experiences->isNotEmpty())
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Experience</h3>
                    @foreach($resume->experiences as $exp)
                        <div style="margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2px;">
                                <strong style="color: #111827; font-size: 0.95rem;">{{ $exp->job_title }}</strong>
                                <span style="color: #9ca3af; font-size: 0.8rem;">{{ $exp->start_date }} – {{ $exp->is_current ? 'Present' : $exp->end_date }}</span>
                            </div>
                            <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 6px;">{{ $exp->company }}{{ $exp->location ? ', ' . $exp->location : '' }}</div>
                            @if($exp->bullets)
                                <ul style="list-style: disc; margin-left: 20px; font-size: 0.875rem; color: #4b5563; line-height: 1.6;">
                                    @foreach($exp->bullets as $bullet)
                                        @if($bullet)<li>{{ $bullet }}</li>@endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if($resume->education->isNotEmpty())
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Education</h3>
                    @foreach($resume->education as $edu)
                        <div style="margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <strong style="color: #111827; font-size: 0.95rem;">{{ $edu->degree }}{{ $edu->field ? ' in ' . $edu->field : '' }}</strong>
                                <span style="color: #9ca3af; font-size: 0.8rem;">{{ $edu->start_date }} – {{ $edu->end_date }}</span>
                            </div>
                            <div style="color: #6b7280; font-size: 0.875rem;">{{ $edu->school }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($resume->skills->isNotEmpty())
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Skills</h3>
                    <p style="font-size: 0.875rem; color: #4b5563;">{{ $resume->skills->pluck('name')->join(' • ') }}</p>
                </div>
            @endif

            @if($resume->projects->isNotEmpty())
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Projects</h3>
                    @foreach($resume->projects as $proj)
                        <div style="margin-bottom: 12px;">
                            <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 2px;">
                                <strong style="color: #111827; font-size: 0.95rem;">{{ $proj->name }}</strong>
                                @if($proj->tech_stack)
                                    <span style="color: #9ca3af; font-size: 0.8rem; font-style: italic;">{{ $proj->tech_stack }}</span>
                                @endif
                            </div>
                            @if($proj->description)
                                <p style="font-size: 0.875rem; color: #4b5563;">{{ $proj->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if($resume->certifications->isNotEmpty())
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Certifications</h3>
                    @foreach($resume->certifications as $cert)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <div>
                                <strong style="color: #111827; font-size: 0.875rem;">{{ $cert->name }}</strong>
                                @if($cert->issuer)<span style="color: #6b7280; font-size: 0.875rem;"> — {{ $cert->issuer }}</span>@endif
                            </div>
                            @if($cert->issue_date)<span style="color: #9ca3af; font-size: 0.8rem;">{{ $cert->issue_date }}</span>@endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
