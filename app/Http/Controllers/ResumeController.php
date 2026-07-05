<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    public function index()
    {
        $resumes = Auth::user()->resumes()
            ->with(['personalInfo', 'latestAtsScore'])
            ->get();
        return view('resumes.index', compact('resumes'));
    }

    public function create()
    {
        return view('resumes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'template' => 'nullable|in:modern,classic,minimal,creative',
        ]);

        $user = Auth::user();

        $resume = $user->resumes()->create([
            'title'      => $data['title'],
            'template'   => $data['template'] ?? 'modern',
            'is_primary' => $user->resumes()->count() === 0,
        ]);

        // Initialize empty personal info
        $resume->personalInfo()->create([]);

        return redirect()->route('resumes.builder', $resume)
            ->with('success', 'Resume created! Start filling in your details.');
    }

    public function builder(Resume $resume)
    {
        $this->authorize('view', $resume);

        $resume->load([
            'personalInfo',
            'education',
            'experiences',
            'skills',
            'projects',
            'certifications',
            'languages',
            'jobDescriptions.atsScore',
        ]);

        return view('resumes.builder', compact('resume'));
    }

    public function update(Request $request, Resume $resume)
    {
        $this->authorize('update', $resume);

        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'template' => 'nullable|in:modern,classic,minimal,creative',
        ]);

        $resume->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'resume' => $resume]);
        }

        return back()->with('success', 'Resume updated.');
    }

    public function destroy(Resume $resume)
    {
        $this->authorize('delete', $resume);

        $wasPrimary = $resume->is_primary;
        $userId     = $resume->user_id;

        $resume->delete();

        // Reassign primary if needed
        if ($wasPrimary) {
            $next = Resume::where('user_id', $userId)->latest()->first();
            $next?->update(['is_primary' => true]);
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Resume deleted.');
    }

    public function setPrimary(Resume $resume)
    {
        $this->authorize('update', $resume);

        DB::transaction(function () use ($resume) {
            Resume::where('user_id', $resume->user_id)
                ->update(['is_primary' => false]);
            $resume->update(['is_primary' => true]);
        });

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', "\"{$resume->title}\" is now your primary resume.");
    }

    public function preview(Resume $resume)
    {
        $this->authorize('view', $resume);
        $resume->load([
            'personalInfo', 'education', 'experiences',
            'skills', 'projects', 'certifications', 'languages',
        ]);
        return view('resumes.preview', compact('resume'));
    }
}
