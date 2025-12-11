<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


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
        return Str::slug($this->judul); 
    }

    public function author(): BelongsTo
    {
        // posts.blade.php memanggil $post->author->...
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function getCategoryAttribute()
    {
        // Ini akan mengembalikan objek dengan property 'name' dan 'slug' agar tidak error
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