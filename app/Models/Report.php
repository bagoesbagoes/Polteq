<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'user_id',
        'type',
        'title',
        'description',
        'file_path',
        'file_size',
        'file_type',
        'hyperlink',
        'luaran_type',
    ];

    /**
     * Report belongs to Proposal
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Report belongs to User (Publisher)
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: Filter by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeHumanAttribute()
    {
        if (!$this->file_size) {
            return '-';
        }
        
        $bytes = $this->file_size * 1024; // Convert KB to bytes
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get file extension icon
     */
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => '📄',
            'doc' => '📝',
            'docx' => '📝',
            'xls' => '📊',
            'xlsx' => '📊',
            'ppt' => '📽️',
            'pptx' => '📽️',
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'zip' => '📦',
            'rar' => '📦',
        ];
        
        return $icons[$this->file_type] ?? '📎';
    }
}