<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    protected $fillable = [
        'resume_id', 'job_title', 'company', 'location',
        'start_date', 'end_date', 'is_current', 'bullets', 'sort_order',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'bullets'    => 'array',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}
