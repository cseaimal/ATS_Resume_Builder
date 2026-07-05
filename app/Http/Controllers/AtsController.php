<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\JobDescription;
use App\Services\AtsScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtsController extends Controller
{
    public function __construct(private AtsScoreService $atsService) {}

    /**
     * Score a resume against a submitted job description.
     */
    public function score(Request $request, Resume $resume)
    {
        $this->authorize('update', $resume);

        $data = $request->validate([
            'jd_title'     => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'raw_text'     => 'required|string|min:50|max:10000',
        ]);

        // Load all resume sections
        $resume->load([
            'personalInfo', 'experiences', 'skills',
            'education', 'projects', 'certifications', 'languages',
        ]);

        // Create job description record
        $jd = JobDescription::create([
            'resume_id'    => $resume->id,
            'jd_title'     => $data['jd_title'] ?? 'Job Description',
            'company_name' => $data['company_name'] ?? '',
            'raw_text'     => $data['raw_text'],
        ]);

        // Run ATS scoring engine
        $atsScore = $this->atsService->score($resume, $jd);

        if ($request->expectsJson()) {
            return response()->json([
                'success'          => true,
                'score'            => $atsScore->overall_score,
                'label'            => $atsScore->score_label,
                'color'            => $atsScore->score_color,
                'matched_keywords' => $atsScore->matched_keywords,
                'missing_keywords' => $atsScore->missing_keywords,
                'flagged_issues'   => $atsScore->flagged_issues,
                'section_scores'   => $atsScore->section_scores,
                'jd_id'            => $jd->id,
                'scored_at'        => $atsScore->scored_at->format('M d, Y H:i'),
            ]);
        }

        return redirect()->route('resumes.ats.history', $resume)
            ->with('success', 'ATS score calculated successfully!');
    }

    /**
     * Show ATS score history for a resume.
     */
    public function history(Resume $resume)
    {
        $this->authorize('view', $resume);

        $resume->load([
            'jobDescriptions.atsScore',
            'personalInfo',
        ]);

        $history = $resume->jobDescriptions()
            ->with('atsScore')
            ->latest()
            ->paginate(10);

        return view('ats.history', compact('resume', 'history'));
    }

    /**
     * Cross-version ATS score comparison for all user resumes.
     */
    public function compare()
    {
        $resumes = Auth::user()->resumes()
            ->with(['personalInfo', 'latestAtsScore'])
            ->get();

        $comparison = $this->atsService->compareVersions($resumes->all());

        return view('ats.compare', compact('resumes', 'comparison'));
    }

    /**
     * Delete a job description + its ATS score.
     */
    public function deleteJd(Resume $resume, JobDescription $jobDescription)
    {
        $this->authorize('update', $resume);
        $jobDescription->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Job description record deleted.');
    }
}
