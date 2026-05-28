<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteRecord extends Model
{
    protected $fillable = [
        'survey_id',
        'surveyor_id',
        'candidacy_id',
        'respondent_type',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function candidacy(): BelongsTo
    {
        return $this->belongsTo(Candidacy::class);
    }

    public function surveyor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'surveyor_id');
    }
}
