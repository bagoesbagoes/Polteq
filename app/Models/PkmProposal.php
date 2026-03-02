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

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-gray-600 text-gray-200">
                                Draft
                            </span>',
            'submitted' => '<span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-blue-600 text-white">
                                Submitted
                            </span>',
            'accepted' => ' <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-green-600 text-white">
                                Accepted
                            </span>',
            'need_revision' => '<span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-yellow-600 text-white">
                                    Need Revision
                                </span>',
        ];
        
        return $badges[$this->status] ?? '<span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-gray-500 text-white">Unknown</span>';
    }

}
