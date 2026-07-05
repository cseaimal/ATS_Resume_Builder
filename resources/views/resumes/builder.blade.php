@extends('layouts.app')

@section('content')
<div class="builder-layout" x-data="resumeBuilder()">
    <!-- Sidebar / Editor -->
    <aside class="builder-sidebar">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold" style="margin-bottom:0;">{{ $resume->title }}</h2>
            <a href="{{ route('dashboard') }}" class="text-muted" style="font-size:0.875rem;">← Dashboard</a>
        </div>

        <div class="tabs">
            <button class="tab" :class="{ 'active': activeTab === 'personal' }" @click="activeTab = 'personal'">Personal</button>
            <button class="tab" :class="{ 'active': activeTab === 'experience' }" @click="activeTab = 'experience'">Experience</button>
            <button class="tab" :class="{ 'active': activeTab === 'education' }" @click="activeTab = 'education'">Education</button>
            <button class="tab" :class="{ 'active': activeTab === 'skills' }" @click="activeTab = 'skills'">Skills</button>
            <button class="tab" :class="{ 'active': activeTab === 'projects' }" @click="activeTab = 'projects'">Projects</button>
            <button class="tab" :class="{ 'active': activeTab === 'certifications' }" @click="activeTab = 'certifications'">Certs</button>
        </div>

        <div class="editor-content" style="position: relative;">

            <!-- Personal Info Tab -->
            <div x-show="activeTab === 'personal'" x-transition.opacity>
                <form @submit.prevent="savePersonalInfo">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" x-model="personalInfo.full_name" class="form-control" placeholder="Jane Smith">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Title / Headline</label>
                        <input type="text" x-model="personalInfo.job_title" class="form-control" placeholder="Senior Software Engineer">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" x-model="personalInfo.email" class="form-control" placeholder="jane@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" x-model="personalInfo.phone" class="form-control" placeholder="+1 555 000 0000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" x-model="personalInfo.location" class="form-control" placeholder="San Francisco, CA">
                    </div>
                    <div class="form-group">
                        <label class="form-label">LinkedIn URL</label>
                        <input type="text" x-model="personalInfo.linkedin" class="form-control" placeholder="linkedin.com/in/janesmith">
                    </div>
                    <div class="form-group">
                        <label class="form-label">GitHub URL</label>
                        <input type="text" x-model="personalInfo.github" class="form-control" placeholder="github.com/janesmith">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Professional Summary</label>
                        <textarea x-model="personalInfo.summary" class="form-control" rows="5" placeholder="A concise summary that highlights your top skills and experience relevant to the role..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Save Personal Info</button>
                </form>
            </div>

            <!-- Experience Tab -->
            <div x-show="activeTab === 'experience'" style="display: none;" x-transition.opacity>
                <template x-for="(exp, index) in experiences" :key="exp.id || index">
                    <div class="glass-card mb-4" style="border: 1px solid rgba(255,255,255,0.08); padding: 16px;">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-bold text-white" style="margin-bottom:0;" x-text="exp.job_title || 'New Experience'"></h4>
                            <button @click="deleteExperience(exp.id, index)" type="button" style="color:#f87171;font-size:0.8rem;background:none;border:none;cursor:pointer;">Delete</button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Job Title</label>
                            <input type="text" x-model="exp.job_title" class="form-control" @change="saveExperience(index)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company</label>
                            <input type="text" x-model="exp.company" class="form-control" @change="saveExperience(index)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <input type="text" x-model="exp.location" class="form-control" @change="saveExperience(index)" placeholder="City, State or Remote">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <input type="text" x-model="exp.start_date" class="form-control" placeholder="Jan 2022" @change="saveExperience(index)">
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <input type="text" x-model="exp.end_date" class="form-control" placeholder="Present" @change="saveExperience(index)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bullet Points <span style="color:var(--text-secondary);font-size:0.8rem;">(one per line, quantify achievements)</span></label>
                            <div>
                                <template x-for="(bullet, bi) in exp.bullets_list" :key="bi">
                                    <div class="bullet-item">
                                        <span style="color:var(--text-secondary);">•</span>
                                        <input type="text" x-model="exp.bullets_list[bi]" class="form-control" @change="saveExperience(index)" placeholder="Led a team of 5 engineers, improving delivery speed by 30%">
                                        <button type="button" class="bullet-remove" @click="removeBullet(index, bi)">×</button>
                                    </div>
                                </template>
                                <button type="button" @click="addBullet(index)" class="btn btn-secondary" style="font-size:0.8rem;padding:6px 12px;margin-top:4px;">+ Add Bullet</button>
                            </div>
                        </div>
                        <button @click="saveExperience(index)" type="button" class="btn btn-primary w-full">Save</button>
                    </div>
                </template>
                <button @click="addExperience" type="button" class="btn btn-secondary w-full" style="border-style:dashed;border-width:2px;padding:16px;margin-top:8px;">
                    + Add Experience
                </button>
            </div>

            <!-- Education Tab -->
            <div x-show="activeTab === 'education'" style="display: none;" x-transition.opacity>
                <template x-for="(edu, index) in education" :key="edu.id || index">
                    <div class="glass-card mb-4" style="border: 1px solid rgba(255,255,255,0.08); padding: 16px;">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-bold text-white" style="margin-bottom:0;" x-text="edu.school || 'New Education'"></h4>
                            <button @click="deleteEducation(edu.id, index)" type="button" style="color:#f87171;font-size:0.8rem;background:none;border:none;cursor:pointer;">Delete</button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">School / University</label>
                            <input type="text" x-model="edu.school" class="form-control" @change="saveEducation(index)">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Degree</label>
                                <input type="text" x-model="edu.degree" class="form-control" @change="saveEducation(index)" placeholder="B.S.">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Field of Study</label>
                                <input type="text" x-model="edu.field" class="form-control" @change="saveEducation(index)" placeholder="Computer Science">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Start Year</label>
                                <input type="text" x-model="edu.start_date" class="form-control" placeholder="2018" @change="saveEducation(index)">
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Year</label>
                                <input type="text" x-model="edu.end_date" class="form-control" placeholder="2022" @change="saveEducation(index)">
                            </div>
                        </div>
                        <button @click="saveEducation(index)" type="button" class="btn btn-primary w-full">Save</button>
                    </div>
                </template>
                <button @click="addEducation" type="button" class="btn btn-secondary w-full" style="border-style:dashed;border-width:2px;padding:16px;margin-top:8px;">
                    + Add Education
                </button>
            </div>

            <!-- Skills Tab -->
            <div x-show="activeTab === 'skills'" style="display: none;" x-transition.opacity>
                <div class="glass-card mb-4" style="border: 1px solid rgba(255,255,255,0.08);">
                    <p class="text-muted text-sm mb-4">Add skills that appear on the job description to boost your ATS score.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <template x-for="(skill, index) in skills" :key="skill.id || index">
                            <div style="background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.4);color:var(--text-primary);padding:6px 12px;border-radius:999px;display:flex;align-items:center;gap:8px;font-size:0.875rem;">
                                <span x-text="skill.name"></span>
                                <button @click="deleteSkill(skill.id, index)" type="button" style="background:none;border:none;color:#a78bfa;cursor:pointer;font-size:1rem;padding:0;line-height:1;">×</button>
                            </div>
                        </template>
                    </div>
                    <form @submit.prevent="addSkill" class="flex gap-2">
                        <input type="text" x-model="newSkillName" class="form-control" placeholder="e.g. React, Python, SQL..." style="flex:1;">
                        <button type="submit" class="btn btn-secondary">Add</button>
                    </form>
                </div>
            </div>

            <!-- Projects Tab -->
            <div x-show="activeTab === 'projects'" style="display: none;" x-transition.opacity>
                <template x-for="(proj, index) in projects" :key="proj.id || index">
                    <div class="glass-card mb-4" style="border: 1px solid rgba(255,255,255,0.08); padding: 16px;">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-bold text-white" style="margin-bottom:0;" x-text="proj.name || 'New Project'"></h4>
                            <button @click="deleteProject(proj.id, index)" type="button" style="color:#f87171;font-size:0.8rem;background:none;border:none;cursor:pointer;">Delete</button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Project Name</label>
                            <input type="text" x-model="proj.name" class="form-control" @change="saveProject(index)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tech Stack (comma separated)</label>
                            <input type="text" x-model="proj.tech_stack" class="form-control" @change="saveProject(index)" placeholder="React, Node.js, PostgreSQL">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Project Link</label>
                            <input type="text" x-model="proj.link" class="form-control" @change="saveProject(index)" placeholder="https://github.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea x-model="proj.description" class="form-control" rows="3" @change="saveProject(index)"></textarea>
                        </div>
                        <button @click="saveProject(index)" type="button" class="btn btn-primary w-full">Save</button>
                    </div>
                </template>
                <button @click="addProject" type="button" class="btn btn-secondary w-full" style="border-style:dashed;border-width:2px;padding:16px;margin-top:8px;">
                    + Add Project
                </button>
            </div>

            <!-- Certs Tab -->
            <div x-show="activeTab === 'certifications'" style="display: none;" x-transition.opacity>
                <template x-for="(cert, index) in certifications" :key="cert.id || index">
                    <div class="glass-card mb-4" style="border: 1px solid rgba(255,255,255,0.08); padding: 16px;">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-bold text-white" style="margin-bottom:0;" x-text="cert.name || 'New Certification'"></h4>
                            <button @click="deleteCertification(cert.id, index)" type="button" style="color:#f87171;font-size:0.8rem;background:none;border:none;cursor:pointer;">Delete</button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Certification Name</label>
                            <input type="text" x-model="cert.name" class="form-control" @change="saveCertification(index)" placeholder="AWS Certified Developer">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Issuer</label>
                                <input type="text" x-model="cert.issuer" class="form-control" @change="saveCertification(index)" placeholder="Amazon Web Services">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Issue Date</label>
                                <input type="text" x-model="cert.issue_date" class="form-control" @change="saveCertification(index)" placeholder="Jan 2023">
                            </div>
                        </div>
                        <button @click="saveCertification(index)" type="button" class="btn btn-primary w-full">Save</button>
                    </div>
                </template>
                <button @click="addCertification" type="button" class="btn btn-secondary w-full" style="border-style:dashed;border-width:2px;padding:16px;margin-top:8px;">
                    + Add Certification
                </button>
            </div>

            <!-- Toast Notification -->
            <div x-show="toast.show" x-transition.opacity
                 style="position:fixed;bottom:24px;right:24px;z-index:200;padding:12px 20px;border-radius:8px;font-size:0.9rem;font-weight:500;display:flex;align-items:center;gap:8px;box-shadow:0 4px 20px rgba(0,0,0,0.4);"
                 :style="'background:' + (toast.error ? '#ef4444' : '#10b981') + '; color: white;'">
                <span x-text="toast.message"></span>
            </div>
        </div>
    </aside>

    <!-- Live Preview -->
    <section class="builder-preview">
        <div class="w-full flex justify-end gap-4 mb-6" style="max-width: 210mm;">
            <a href="{{ route('resumes.preview', $resume) }}" target="_blank" class="btn btn-secondary">Full Preview</a>
            <button @click="testATS" class="btn btn-primary" style="background: linear-gradient(135deg, #10b981, #059669);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Test ATS Score
            </button>
            <a href="{{ route('resumes.export.pdf', $resume) }}" target="_blank" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export PDF
            </a>
        </div>

        <div class="a4-paper shadow-2xl">
            <!-- Resume Preview -->
            <div style="border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; text-align: center;">
                <h1 style="font-size: 2rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 4px; color: #111827;" x-text="personalInfo.full_name || 'YOUR NAME'"></h1>
                <h2 style="font-size: 1.05rem; color: #6b7280; margin-bottom: 10px; font-weight: 400;" x-text="personalInfo.job_title || 'Professional Title'"></h2>
                <div style="display:flex;justify-content:center;gap:12px;font-size:0.8rem;color:#9ca3af;flex-wrap:wrap;">
                    <span x-show="personalInfo.email" x-text="personalInfo.email"></span>
                    <span x-show="personalInfo.phone" x-text="'• ' + personalInfo.phone"></span>
                    <span x-show="personalInfo.location" x-text="'• ' + personalInfo.location"></span>
                    <span x-show="personalInfo.linkedin" x-text="'• ' + personalInfo.linkedin"></span>
                </div>
            </div>

            <div x-show="personalInfo.summary" style="margin-bottom: 20px;">
                <h3 style="font-size: 0.8rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Summary</h3>
                <p style="font-size: 0.82rem; color: #4b5563; line-height: 1.7;" x-text="personalInfo.summary"></p>
            </div>

            <div x-show="experiences.length > 0" style="margin-bottom: 20px;">
                <h3 style="font-size: 0.8rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Experience</h3>
                <template x-for="exp in experiences">
                    <div style="margin-bottom: 14px;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:2px;">
                            <strong style="color:#111827;font-size:0.9rem;" x-text="exp.job_title"></strong>
                            <span style="font-size:0.75rem;color:#9ca3af;" x-text="(exp.start_date || '') + ' — ' + (exp.end_date || 'Present')"></span>
                        </div>
                        <div style="font-size:0.82rem;color:#6b7280;margin-bottom:6px;" x-text="exp.company + (exp.location ? ', ' + exp.location : '')"></div>
                        <ul style="list-style:disc;margin-left:16px;font-size:0.8rem;color:#4b5563;line-height:1.6;">
                            <template x-for="bullet in (exp.bullets_list || []).filter(b => b.trim())">
                                <li x-text="bullet"></li>
                            </template>
                        </ul>
                    </div>
                </template>
            </div>

            <div x-show="education.length > 0" style="margin-bottom: 20px;">
                <h3 style="font-size: 0.8rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Education</h3>
                <template x-for="edu in education">
                    <div style="margin-bottom: 10px;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:2px;">
                            <strong style="color:#111827;font-size:0.9rem;" x-text="(edu.degree || '') + (edu.field ? ' in ' + edu.field : '')"></strong>
                            <span style="font-size:0.75rem;color:#9ca3af;" x-text="(edu.start_date || '') + ' — ' + (edu.end_date || '')"></span>
                        </div>
                        <div style="font-size:0.82rem;color:#6b7280;" x-text="edu.school"></div>
                    </div>
                </template>
            </div>

            <div x-show="skills.length > 0" style="margin-bottom: 20px;">
                <h3 style="font-size: 0.8rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Skills</h3>
                <p style="font-size:0.82rem;color:#4b5563;">
                    <template x-for="(skill, idx) in skills">
                        <span><span x-text="skill.name"></span><span x-show="idx < skills.length - 1"> • </span></span>
                    </template>
                </p>
            </div>

            <div x-show="projects.length > 0" style="margin-bottom: 20px;">
                <h3 style="font-size: 0.8rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Projects</h3>
                <template x-for="proj in projects">
                    <div style="margin-bottom: 10px;">
                        <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:2px;">
                            <strong style="color:#111827;font-size:0.9rem;" x-text="proj.name"></strong>
                            <span style="font-size:0.75rem;color:#9ca3af;font-style:italic;" x-text="proj.tech_stack"></span>
                        </div>
                        <div style="font-size:0.8rem;color:#4b5563;" x-text="proj.description"></div>
                    </div>
                </template>
            </div>

            <div x-show="certifications.length > 0" style="margin-bottom: 20px;">
                <h3 style="font-size: 0.8rem; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; color: #374151;">Certifications</h3>
                <template x-for="cert in certifications">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <div>
                            <strong style="color:#111827;font-size:0.9rem;" x-text="cert.name"></strong>
                            <span style="font-size:0.82rem;color:#6b7280;" x-show="cert.issuer" x-text="' — ' + cert.issuer"></span>
                        </div>
                        <span style="font-size:0.75rem;color:#9ca3af;" x-text="cert.issue_date"></span>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- ATS Modal -->
    <div x-show="showAtsModal" style="display: none;" class="fixed inset-0 backdrop-blur-sm" style="z-index:100;" x-transition.opacity>
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:100;display:flex;align-items:center;justify-content:center;padding:24px;" @click.self="showAtsModal = false">
            <div class="glass-card" style="width:100%;max-width:640px;background:#0f1629;border:1px solid rgba(255,255,255,0.1);position:relative;">
                <button @click="showAtsModal = false" style="position:absolute;top:16px;right:16px;background:none;border:none;color:var(--text-secondary);font-size:1.5rem;cursor:pointer;line-height:1;">✕</button>

                <h2 class="text-2xl font-bold mb-2">ATS Score Test</h2>
                <p class="text-muted mb-6" style="font-size:0.9rem;">Paste a job description to see how well your resume matches its keywords.</p>

                <form @submit.prevent="submitAtsTest" x-show="!atsResult">
                    <div class="form-group">
                        <label class="form-label">Paste Job Description</label>
                        <textarea x-model="atsJd" class="form-control" rows="10" required placeholder="Paste the full job description here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full" :disabled="isScoring">
                        <span x-show="!isScoring">Calculate ATS Score</span>
                        <span x-show="isScoring">Analyzing...</span>
                    </button>
                </form>

                <div x-show="atsResult" class="text-center" x-transition.opacity>
                    <div class="score-circle" :style="'border-color: ' + atsResult?.color + '; color: ' + atsResult?.color">
                        <div class="score-num" x-text="atsResult?.score"></div>
                        <div class="score-label" x-text="atsResult?.label"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-left mb-6">
                        <div style="background:rgba(52,211,153,0.07);border:1px solid rgba(52,211,153,0.15);padding:16px;border-radius:8px;">
                            <h4 style="font-weight:700;color:#34d399;margin-bottom:10px;font-size:0.9rem;">✓ Matched Keywords</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="kw in atsResult?.matched_keywords?.slice(0, 12)">
                                    <span style="background:rgba(52,211,153,0.1);color:#34d399;font-size:0.75rem;padding:3px 8px;border-radius:4px;" x-text="kw"></span>
                                </template>
                            </div>
                        </div>
                        <div style="background:rgba(248,113,113,0.07);border:1px solid rgba(248,113,113,0.15);padding:16px;border-radius:8px;">
                            <h4 style="font-weight:700;color:#f87171;margin-bottom:10px;font-size:0.9rem;">✗ Missing Keywords</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="kw in atsResult?.missing_keywords?.slice(0, 12)">
                                    <span style="background:rgba(248,113,113,0.1);color:#f87171;font-size:0.75rem;padding:3px 8px;border-radius:4px;" x-text="kw"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div x-show="atsResult?.flagged_issues?.length > 0" style="margin-bottom:16px;text-align:left;">
                        <h4 style="font-weight:700;font-size:0.85rem;color:var(--text-secondary);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">Issues Found</h4>
                        <template x-for="issue in atsResult?.flagged_issues?.slice(0,4)">
                            <div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);color:#fbbf24;padding:8px 12px;border-radius:6px;font-size:0.82rem;margin-bottom:6px;" x-text="issue.message"></div>
                        </template>
                    </div>

                    <div class="flex gap-4">
                        <a :href="`/resumes/${resumeId}/ats-history`" class="btn btn-secondary" style="flex:1;">View Full History</a>
                        <button @click="atsResult = null; atsJd = ''" type="button" class="btn btn-primary" style="flex:1;">Test Again</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('resumeBuilder', () => ({
            activeTab: 'personal',
            resumeId: {{ $resume->id }},
            toast: { show: false, message: '', error: false },

            personalInfo: Object.assign({
                full_name: '', job_title: '', email: '', phone: '',
                location: '', linkedin: '', github: '', website: '', summary: ''
            }, @json($resume->personalInfo ?? new \stdClass())),

            experiences: @json($resume->experiences ?? []),
            education: @json($resume->education ?? []),
            skills: @json($resume->skills ?? []),
            projects: @json($resume->projects ?? []),
            certifications: @json($resume->certifications ?? []),

            newSkillName: '',
            showAtsModal: false,
            atsJd: '',
            isScoring: false,
            atsResult: null,

            init() {
                // Convert stored JSON bullets array to display list
                this.experiences.forEach(exp => {
                    exp.bullets_list = Array.isArray(exp.bullets) && exp.bullets.length > 0
                        ? [...exp.bullets]
                        : [''];
                });
            },

            showToast(msg, error = false) {
                this.toast = { show: true, message: msg, error };
                setTimeout(() => this.toast.show = false, 2500);
            },

            getCsrf() {
                return document.querySelector('meta[name="csrf-token"]').content;
            },

            // ---- Personal Info ----
            async savePersonalInfo() {
                try {
                    const res = await fetch(`/resumes/${this.resumeId}/personal-info`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf() },
                        body: JSON.stringify(this.personalInfo)
                    });
                    if (res.ok) this.showToast('Personal info saved!');
                    else this.showToast('Save failed', true);
                } catch (e) { this.showToast('Network error', true); }
            },

            // ---- Bullets ----
            addBullet(expIndex) {
                this.experiences[expIndex].bullets_list.push('');
            },
            removeBullet(expIndex, bulletIndex) {
                this.experiences[expIndex].bullets_list.splice(bulletIndex, 1);
                if (this.experiences[expIndex].bullets_list.length === 0) {
                    this.experiences[expIndex].bullets_list = [''];
                }
                this.saveExperience(expIndex);
            },

            // ---- Experiences ----
            addExperience() {
                this.experiences.push({ job_title: '', company: '', location: '', start_date: '', end_date: '', bullets_list: [''] });
            },
            async saveExperience(index) {
                const exp = this.experiences[index];
                const payload = { ...exp, bullets: exp.bullets_list.filter(b => b.trim()) };
                const url = exp.id ? `/resumes/${this.resumeId}/experiences/${exp.id}` : `/resumes/${this.resumeId}/experiences`;
                const method = exp.id ? 'PATCH' : 'POST';
                try {
                    const res = await fetch(url, {
                        method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf() },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.experiences[index].id = data.data.id;
                        this.showToast('Saved!');
                    }
                } catch (e) { this.showToast('Error saving', true); }
            },
            async deleteExperience(id, index) {
                if (!id) return this.experiences.splice(index, 1);
                if (confirm('Delete this experience?')) {
                    const res = await fetch(`/resumes/${this.resumeId}/experiences/${id}`, {
                        method: 'DELETE', headers: { 'X-CSRF-TOKEN': this.getCsrf() }
                    });
                    if (res.ok) { this.experiences.splice(index, 1); this.showToast('Deleted'); }
                }
            },

            // ---- Education ----
            addEducation() {
                this.education.push({ school: '', degree: '', field: '', start_date: '', end_date: '' });
            },
            async saveEducation(index) {
                const item = this.education[index];
                const url = item.id ? `/resumes/${this.resumeId}/education/${item.id}` : `/resumes/${this.resumeId}/education`;
                const method = item.id ? 'PATCH' : 'POST';
                const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf() }, body: JSON.stringify(item) });
                const data = await res.json();
                if (data.success) { this.education[index].id = data.data.id; this.showToast('Saved!'); }
            },
            async deleteEducation(id, index) {
                if (!id) return this.education.splice(index, 1);
                if (confirm('Delete this education?')) {
                    const res = await fetch(`/resumes/${this.resumeId}/education/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': this.getCsrf() } });
                    if (res.ok) { this.education.splice(index, 1); this.showToast('Deleted'); }
                }
            },

            // ---- Skills ----
            async addSkill() {
                if (!this.newSkillName.trim()) return;
                const res = await fetch(`/resumes/${this.resumeId}/skills`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf() },
                    body: JSON.stringify({ name: this.newSkillName.trim() })
                });
                const data = await res.json();
                if (data.success) { this.skills.push(data.data); this.newSkillName = ''; this.showToast('Skill added!'); }
            },
            async deleteSkill(id, index) {
                if (confirm('Remove this skill?')) {
                    const res = await fetch(`/resumes/${this.resumeId}/skills/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': this.getCsrf() } });
                    if (res.ok) { this.skills.splice(index, 1); }
                }
            },

            // ---- Projects ----
            addProject() { this.projects.push({ name: '', tech_stack: '', link: '', description: '' }); },
            async saveProject(index) {
                const item = this.projects[index];
                const url = item.id ? `/resumes/${this.resumeId}/projects/${item.id}` : `/resumes/${this.resumeId}/projects`;
                const method = item.id ? 'PATCH' : 'POST';
                const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf() }, body: JSON.stringify(item) });
                const data = await res.json();
                if (data.success) { this.projects[index].id = data.data.id; this.showToast('Saved!'); }
            },
            async deleteProject(id, index) {
                if (!id) return this.projects.splice(index, 1);
                if (confirm('Delete this project?')) {
                    const res = await fetch(`/resumes/${this.resumeId}/projects/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': this.getCsrf() } });
                    if (res.ok) { this.projects.splice(index, 1); this.showToast('Deleted'); }
                }
            },

            // ---- Certifications ----
            addCertification() { this.certifications.push({ name: '', issuer: '', issue_date: '' }); },
            async saveCertification(index) {
                const item = this.certifications[index];
                const url = item.id ? `/resumes/${this.resumeId}/certifications/${item.id}` : `/resumes/${this.resumeId}/certifications`;
                const method = item.id ? 'PATCH' : 'POST';
                const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf() }, body: JSON.stringify(item) });
                const data = await res.json();
                if (data.success) { this.certifications[index].id = data.data.id; this.showToast('Saved!'); }
            },
            async deleteCertification(id, index) {
                if (!id) return this.certifications.splice(index, 1);
                if (confirm('Delete this certification?')) {
                    const res = await fetch(`/resumes/${this.resumeId}/certifications/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': this.getCsrf() } });
                    if (res.ok) { this.certifications.splice(index, 1); this.showToast('Deleted'); }
                }
            },

            // ---- ATS ----
            testATS() { this.showAtsModal = true; this.atsResult = null; this.atsJd = ''; },
            async submitAtsTest() {
                this.isScoring = true;
                try {
                    const res = await fetch(`/resumes/${this.resumeId}/ats-score`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf(), 'Accept': 'application/json' },
                        body: JSON.stringify({ raw_text: this.atsJd })
                    });
                    const data = await res.json();
                    if (data.success) this.atsResult = data;
                    else this.showToast(data.message || 'Scoring failed', true);
                } catch (e) {
                    this.showToast('Error calculating ATS score', true);
                } finally {
                    this.isScoring = false;
                }
            }
        }));
    });
</script>
@endsection
