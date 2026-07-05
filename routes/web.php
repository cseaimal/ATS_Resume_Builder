<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AtsController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resumes CRUD
    Route::get('/resumes', [ResumeController::class, 'index'])->name('resumes.index');
    Route::get('/resumes/create', [ResumeController::class, 'create'])->name('resumes.create');
    Route::post('/resumes', [ResumeController::class, 'store'])->name('resumes.store');
    Route::get('/resumes/{resume}/builder', [ResumeController::class, 'builder'])->name('resumes.builder');
    Route::patch('/resumes/{resume}', [ResumeController::class, 'update'])->name('resumes.update');
    Route::delete('/resumes/{resume}', [ResumeController::class, 'destroy'])->name('resumes.destroy');
    Route::post('/resumes/{resume}/set-primary', [ResumeController::class, 'setPrimary'])->name('resumes.set-primary');
    Route::get('/resumes/{resume}/preview', [ResumeController::class, 'preview'])->name('resumes.preview');

    // Section APIs
    Route::post('/resumes/{resume}/personal-info', [SectionController::class, 'updatePersonalInfo'])->name('resumes.sections.personal-info');
    
    Route::post('/resumes/{resume}/education', [SectionController::class, 'storeEducation'])->name('resumes.sections.education.store');
    Route::patch('/resumes/{resume}/education/{education}', [SectionController::class, 'updateEducation'])->name('resumes.sections.education.update');
    Route::delete('/resumes/{resume}/education/{education}', [SectionController::class, 'destroyEducation'])->name('resumes.sections.education.destroy');
    
    Route::post('/resumes/{resume}/experiences', [SectionController::class, 'storeExperience'])->name('resumes.sections.experiences.store');
    Route::patch('/resumes/{resume}/experiences/{experience}', [SectionController::class, 'updateExperience'])->name('resumes.sections.experiences.update');
    Route::delete('/resumes/{resume}/experiences/{experience}', [SectionController::class, 'destroyExperience'])->name('resumes.sections.experiences.destroy');
    
    Route::post('/resumes/{resume}/skills', [SectionController::class, 'storeSkill'])->name('resumes.sections.skills.store');
    Route::patch('/resumes/{resume}/skills/{skill}', [SectionController::class, 'updateSkill'])->name('resumes.sections.skills.update');
    Route::delete('/resumes/{resume}/skills/{skill}', [SectionController::class, 'destroySkill'])->name('resumes.sections.skills.destroy');
    
    Route::post('/resumes/{resume}/projects', [SectionController::class, 'storeProject'])->name('resumes.sections.projects.store');
    Route::patch('/resumes/{resume}/projects/{project}', [SectionController::class, 'updateProject'])->name('resumes.sections.projects.update');
    Route::delete('/resumes/{resume}/projects/{project}', [SectionController::class, 'destroyProject'])->name('resumes.sections.projects.destroy');

    Route::post('/resumes/{resume}/certifications', [SectionController::class, 'storeCertification'])->name('resumes.sections.certifications.store');
    Route::patch('/resumes/{resume}/certifications/{certification}', [SectionController::class, 'updateCertification'])->name('resumes.sections.certifications.update');
    Route::delete('/resumes/{resume}/certifications/{certification}', [SectionController::class, 'destroyCertification'])->name('resumes.sections.certifications.destroy');

    Route::post('/resumes/{resume}/languages', [SectionController::class, 'storeLanguage'])->name('resumes.sections.languages.store');
    Route::patch('/resumes/{resume}/languages/{language}', [SectionController::class, 'updateLanguage'])->name('resumes.sections.languages.update');
    Route::delete('/resumes/{resume}/languages/{language}', [SectionController::class, 'destroyLanguage'])->name('resumes.sections.languages.destroy');

    // ATS Engine
    Route::get('/dashboard/ats-compare', [AtsController::class, 'compare'])->name('dashboard.ats-compare');
    Route::post('/resumes/{resume}/ats-score', [AtsController::class, 'score'])->name('resumes.ats.score');
    Route::get('/resumes/{resume}/ats-history', [AtsController::class, 'history'])->name('resumes.ats.history');
    Route::delete('/resumes/{resume}/ats-history/{jobDescription}', [AtsController::class, 'deleteJd'])->name('resumes.ats.delete-jd');

    // Export
    Route::get('/resumes/{resume}/export/pdf', [ExportController::class, 'exportPdf'])->name('resumes.export.pdf');
});

require __DIR__.'/auth.php';
