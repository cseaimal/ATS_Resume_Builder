<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Certification;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    // ==================== PERSONAL INFO ====================

    public function updatePersonalInfo(Request $request, Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);

        $data = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:50',
            'location'  => 'nullable|string|max:255',
            'linkedin'  => 'nullable|string|max:500',
            'github'    => 'nullable|string|max:500',
            'website'   => 'nullable|string|max:500',
            'summary'   => 'nullable|string|max:2000',
        ]);

        $pi = $resume->personalInfo()->updateOrCreate(
            ['resume_id' => $resume->id],
            $data
        );

        return response()->json(['success' => true, 'data' => $pi]);
    }

    // ==================== EDUCATION ====================

    public function storeEducation(Request $request, Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);
        $data = $request->validate($this->educationRules());
        $data['resume_id'] = $resume->id;
        $edu = Education::create($data);
        return response()->json(['success' => true, 'data' => $edu]);
    }

    public function updateEducation(Request $request, Resume $resume, Education $education): JsonResponse
    {
        $this->authorize('update', $resume);
        $education->update($request->validate($this->educationRules()));
        return response()->json(['success' => true, 'data' => $education]);
    }

    public function destroyEducation(Resume $resume, Education $education): JsonResponse
    {
        $this->authorize('update', $resume);
        $education->delete();
        return response()->json(['success' => true]);
    }

    private function educationRules(): array
    {
        return [
            'degree'     => 'nullable|string|max:255',
            'field'      => 'nullable|string|max:255',
            'school'     => 'nullable|string|max:255',
            'start_date' => 'nullable|string|max:20',
            'end_date'   => 'nullable|string|max:20',
            'gpa'        => 'nullable|string|max:10',
            'location'   => 'nullable|string|max:255',
            'description'=> 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer',
        ];
    }

    // ==================== EXPERIENCE ====================

    public function storeExperience(Request $request, Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);
        $data = $request->validate($this->experienceRules());
        $data['resume_id'] = $resume->id;
        $exp = Experience::create($data);
        return response()->json(['success' => true, 'data' => $exp]);
    }

    public function updateExperience(Request $request, Resume $resume, Experience $experience): JsonResponse
    {
        $this->authorize('update', $resume);
        $experience->update($request->validate($this->experienceRules()));
        return response()->json(['success' => true, 'data' => $experience]);
    }

    public function destroyExperience(Resume $resume, Experience $experience): JsonResponse
    {
        $this->authorize('update', $resume);
        $experience->delete();
        return response()->json(['success' => true]);
    }

    private function experienceRules(): array
    {
        return [
            'job_title'  => 'nullable|string|max:255',
            'company'    => 'nullable|string|max:255',
            'location'   => 'nullable|string|max:255',
            'start_date' => 'nullable|string|max:20',
            'end_date'   => 'nullable|string|max:20',
            'is_current' => 'nullable|boolean',
            'bullets'    => 'nullable|array',
            'bullets.*'  => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
        ];
    }

    // ==================== SKILLS ====================

    public function storeSkill(Request $request, Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'category'          => 'nullable|string|max:100',
            'proficiency_level' => 'nullable|string|max:50',
            'sort_order'        => 'nullable|integer',
        ]);
        $data['resume_id'] = $resume->id;
        $skill = Skill::create($data);
        return response()->json(['success' => true, 'data' => $skill]);
    }

    public function updateSkill(Request $request, Resume $resume, Skill $skill): JsonResponse
    {
        $this->authorize('update', $resume);
        $skill->update($request->validate([
            'name'              => 'required|string|max:100',
            'category'          => 'nullable|string|max:100',
            'proficiency_level' => 'nullable|string|max:50',
            'sort_order'        => 'nullable|integer',
        ]));
        return response()->json(['success' => true, 'data' => $skill]);
    }

    public function destroySkill(Resume $resume, Skill $skill): JsonResponse
    {
        $this->authorize('update', $resume);
        $skill->delete();
        return response()->json(['success' => true]);
    }

    // ==================== PROJECTS ====================

    public function storeProject(Request $request, Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);
        $data = $request->validate([
            'name'        => 'nullable|string|max:255',
            'tech_stack'  => 'nullable|string|max:500',
            'link'        => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'sort_order'  => 'nullable|integer',
        ]);
        $data['resume_id'] = $resume->id;
        $project = Project::create($data);
        return response()->json(['success' => true, 'data' => $project]);
    }

    public function updateProject(Request $request, Resume $resume, Project $project): JsonResponse
    {
        $this->authorize('update', $resume);
        $project->update($request->validate([
            'name'        => 'nullable|string|max:255',
            'tech_stack'  => 'nullable|string|max:500',
            'link'        => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'sort_order'  => 'nullable|integer',
        ]));
        return response()->json(['success' => true, 'data' => $project]);
    }

    public function destroyProject(Resume $resume, Project $project): JsonResponse
    {
        $this->authorize('update', $resume);
        $project->delete();
        return response()->json(['success' => true]);
    }

    // ==================== CERTIFICATIONS ====================

    public function storeCertification(Request $request, Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);
        $data = $request->validate([
            'name'           => 'nullable|string|max:255',
            'issuer'         => 'nullable|string|max:255',
            'issue_date'     => 'nullable|string|max:20',
            'expiry_date'    => 'nullable|string|max:20',
            'credential_url' => 'nullable|string|max:500',
        ]);
        $data['resume_id'] = $resume->id;
        $cert = Certification::create($data);
        return response()->json(['success' => true, 'data' => $cert]);
    }

    public function updateCertification(Request $request, Resume $resume, Certification $certification): JsonResponse
    {
        $this->authorize('update', $resume);
        $certification->update($request->validate([
            'name'           => 'nullable|string|max:255',
            'issuer'         => 'nullable|string|max:255',
            'issue_date'     => 'nullable|string|max:20',
            'expiry_date'    => 'nullable|string|max:20',
            'credential_url' => 'nullable|string|max:500',
        ]));
        return response()->json(['success' => true, 'data' => $certification]);
    }

    public function destroyCertification(Resume $resume, Certification $certification): JsonResponse
    {
        $this->authorize('update', $resume);
        $certification->delete();
        return response()->json(['success' => true]);
    }

    // ==================== LANGUAGES ====================

    public function storeLanguage(Request $request, Resume $resume): JsonResponse
    {
        $this->authorize('update', $resume);
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'proficiency_level' => 'nullable|string|max:50',
        ]);
        $data['resume_id'] = $resume->id;
        $lang = Language::create($data);
        return response()->json(['success' => true, 'data' => $lang]);
    }

    public function updateLanguage(Request $request, Resume $resume, Language $language): JsonResponse
    {
        $this->authorize('update', $resume);
        $language->update($request->validate([
            'name'              => 'required|string|max:100',
            'proficiency_level' => 'nullable|string|max:50',
        ]));
        return response()->json(['success' => true, 'data' => $language]);
    }

    public function destroyLanguage(Resume $resume, Language $language): JsonResponse
    {
        $this->authorize('update', $resume);
        $language->delete();
        return response()->json(['success' => true]);
    }
}
