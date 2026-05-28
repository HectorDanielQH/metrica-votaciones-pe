<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    protected $fillable = [
        'name',
        'status',
        'is_active',
        'student_vote_weight',
        'teacher_vote_weight',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'student_vote_weight' => 'decimal:4',
            'teacher_vote_weight' => 'decimal:4',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function voteRecords(): HasMany
    {
        return $this->hasMany(VoteRecord::class);
    }
}
