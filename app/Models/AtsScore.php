<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtsScore extends Model
{
    protected $fillable = [
        'job_description_id', 'resume_id', 'overall_score',
        'matched_keywords', 'missing_keywords', 'flagged_issues',
        'section_scores', 'scored_at',
    ];

    protected $casts = [
        'matched_keywords' => 'array',
        'missing_keywords' => 'array',
        'flagged_issues'   => 'array',
        'section_scores'   => 'array',
        'scored_at'        => 'datetime',
    ];

    public function jobDescription(): BelongsTo
    {
        return $this->belongsTo(JobDescription::class);
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function getScoreColorAttribute(): string
    {
        return match(true) {
            $this->overall_score >= 80 => '#10b981',
            $this->overall_score >= 60 => '#f59e0b',
            $this->overall_score >= 40 => '#f97316',
            default                    => '#ef4444',
        };
    }

    public function getScoreLabelAttribute(): string
    {
        return match(true) {
            $this->overall_score >= 80 => 'Excellent',
            $this->overall_score >= 60 => 'Good',
            $this->overall_score >= 40 => 'Fair',
            default                    => 'Poor',
        };
    }
}
