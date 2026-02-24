<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PkmReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'pkm_prposal_id',
        'reviewer_id',
        'score',
        'scores',
        'total_score',
        'comments',
        'recommendation',
    ];

    protected $casts = [
        'scores' => 'array',
    ];

    // Relationships antar table 
    public function pkmProposal()
    {
        return $this->belongsTo(PkmProposal::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
