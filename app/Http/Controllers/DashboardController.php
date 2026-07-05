<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $resumes = $user->resumes()
            ->with(['personalInfo', 'latestAtsScore'])
            ->withCount(['experiences', 'skills', 'education'])
            ->get();

        $primaryResume = $resumes->firstWhere('is_primary', true)
            ?? $resumes->first();

        $totalScored = $resumes->filter(fn($r) => $r->latestAtsScore !== null)->count();
        $avgScore    = $totalScored > 0
            ? (int) round($resumes->filter(fn($r) => $r->latestAtsScore !== null)
                ->avg(fn($r) => $r->latestAtsScore->overall_score))
            : null;

        return view('dashboard.index', compact(
            'resumes', 'primaryResume', 'totalScored', 'avgScore'
        ));
    }
}
