<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resume extends Model
{
    protected $fillable = [
        'user_id', 'title', 'template', 'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function personalInfo(): HasOne
    {
        return $this->hasOne(PersonalInfo::class);
    }

    public function education(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('sort_order');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class)->orderBy('sort_order');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('sort_order');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->orderBy('sort_order');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(Language::class);
    }

    public function jobDescriptions(): HasMany
    {
        return $this->hasMany(JobDescription::class)->latest();
    }

    public function atsScores(): HasMany
    {
        return $this->hasMany(AtsScore::class)->latest();
    }

    public function latestAtsScore()
    {
        return $this->hasOne(AtsScore::class)->latestOfMany();
    }

    /**
     * Get all resume text concatenated for ATS analysis
     */
    public function getFullTextAttribute(): string
    {
        $parts = [];

        if ($this->personalInfo) {
            $parts[] = $this->personalInfo->full_name;
            $parts[] = $this->personalInfo->job_title;
            $parts[] = $this->personalInfo->summary;
        }

        foreach ($this->experiences as $exp) {
            $parts[] = $exp->job_title;
            $parts[] = $exp->company;
            if ($exp->bullets) {
                $parts = array_merge($parts, $exp->bullets);
            }
        }

        foreach ($this->skills as $skill) {
            $parts[] = $skill->name;
        }

        foreach ($this->education as $edu) {
            $parts[] = $edu->degree;
            $parts[] = $edu->field;
            $parts[] = $edu->school;
        }

        foreach ($this->projects as $proj) {
            $parts[] = $proj->name;
            $parts[] = $proj->tech_stack;
            $parts[] = $proj->description;
        }

        foreach ($this->certifications as $cert) {
            $parts[] = $cert->name;
            $parts[] = $cert->issuer;
        }

        return implode(' ', array_filter($parts));
    }
}
