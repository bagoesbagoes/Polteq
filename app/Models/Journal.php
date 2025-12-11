<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    // Journal dibuat oleh Dosen/Editor
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Journal memiliki banyak Proposals
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'journal_id');
    }
}