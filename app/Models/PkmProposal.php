<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkmProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'tahun_pelaksanaan',
        'sumber_dana', 
        'kategori_pkm',
        'kelompok_riset',
        'anggota_tim',
        'abstrak',
        'file_usulan',
        'file_size',
        'status',
        'revision_notes',
        'submitted_at',
    ];

    protected $casts = [
        'anggota_tim' => 'array',
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(PkmReview::class);
    }

    public function getFileSizeHumanAttribute()
    {
        if (!$this->file_size) return 'N/A';

        if ($this->file_size < 1024) {
            return round($this->file_size, 2). 'KB';
        }
        return round($this->file_size / 1024, 2). 'MB';
    }

    public function getStatusBadgeAtrribute()
    {
        return match ($this->status) {
            'draft' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-600 text-white">Draft</span>',
            'submitted' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-600 text-white">Submitted</span>',
            'accepted' => '<span class="px-2 py-1 text-xs rounded-full bg-green-600 text-white">Accepted</span>',
            'need_revision' => '<span class="px-2 py-1 text-xs rounded-full bg-red-600 text-white">Need Revision</span>',
            default => '<span class="px-2 py-1 text-xs rounded-full bg-gray-600 text-white">Unknown</span>',
        };
    }

}
