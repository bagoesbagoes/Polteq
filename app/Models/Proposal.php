<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;


class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'file_usulan',
        'file_revisi',
        'status',
        'journal_id',
    ];

     public function getSlugAttribute()
    {
        return $this->id . '-' . Str::slug($this->judul);
    }

     public function getTitleAttribute()
    {
        return $this->judul;
    }

     public function getBodyAttribute()
    {
        return $this->deskripsi;
    }

     public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     public function getCategoryAttribute()
    {
        return (object) ['name' => 'Proposal', 'slug' => 'proposal'];
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'reviews', 'proposal_id', 'reviewer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'proposal_id');
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

}