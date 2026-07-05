<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalInfo extends Model
{
    protected $table = 'personal_info';

    protected $fillable = [
        'resume_id', 'full_name', 'job_title', 'email', 'phone',
        'location', 'linkedin', 'github', 'website', 'summary',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}
