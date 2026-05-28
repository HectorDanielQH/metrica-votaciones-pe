<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidacy extends Model
{
    protected $fillable = [
        'party_name',
        'party_logo_path',
        'primary_candidate_name',
        'primary_candidate_photo_path',
        'secondary_candidate_name',
        'secondary_candidate_photo_path',
        'status',
    ];
}
