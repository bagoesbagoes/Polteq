<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'comment',
        'scores',
        'total_score',
        'recommendation',
    ];

     protected $casts = [
        'scores' => 'array',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}