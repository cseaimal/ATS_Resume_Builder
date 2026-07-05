<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export the resume as a PDF.
     */
    public function exportPdf(Resume $resume)
    {
        $this->authorize('view', $resume);

        $resume->load([
            'personalInfo', 'education', 'experiences',
            'skills', 'projects', 'certifications', 'languages',
        ]);

        $pdf = Pdf::loadView('resumes.pdf.modern', compact('resume'));

        return $pdf->download(auth()->user()->name . '_Resume.pdf');
    }
}
