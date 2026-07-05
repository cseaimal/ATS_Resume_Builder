<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobDescription extends Model
{
    protected $fillable = [
        'resume_id', 'jd_title', 'company_name', 'raw_text',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function atsScore(): HasOne
    {
        return $this->hasOne(AtsScore::class);
    }
}
