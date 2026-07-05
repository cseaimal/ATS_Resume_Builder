@extends('layouts.app')

@section('content')
<div class="container mt-12 max-w-2xl mx-auto">
    <div class="glass-card">
        <h1 class="text-2xl font-bold mb-2">Create New Resume</h1>
        <p class="text-muted mb-6">Start building your ATS-optimized resume. You can change these settings later.</p>

        <form action="{{ route('resumes.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="title" class="form-label">Resume Title (Internal Name)</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="e.g., Software Engineer - TechCorp" value="{{ old('title') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Design Template</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="template" value="modern" class="peer sr-only" checked>
                        <div class="p-4 border border-gray-700 rounded-lg peer-checked:border-violet-500 peer-checked:bg-violet-900/20 hover:bg-white/5 transition">
                            <div class="font-medium text-white mb-1">Modern</div>
                            <div class="text-xs text-muted">Clean, two-column layout ideal for tech</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="template" value="classic" class="peer sr-only">
                        <div class="p-4 border border-gray-700 rounded-lg peer-checked:border-violet-500 peer-checked:bg-violet-900/20 hover:bg-white/5 transition">
                            <div class="font-medium text-white mb-1">Classic</div>
                            <div class="text-xs text-muted">Traditional one-column, highly ATS readable</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <button type="submit" class="btn btn-primary flex-1">Create & Start Editing</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
