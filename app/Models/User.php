<?php

namespace App\Models;

use App\Models\Proposal;
use App\Models\Review;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'nidn_nuptk',
        'jabatan_fungsional',
        'prodi',
    ];              

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}