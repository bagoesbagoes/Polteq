<?php

namespace App\Helpers;

class ProposalHelper
{
    /**
     * Get Tailwind CSS classes for proposal status badge
     * 
     * @param string $status
     * @return string
     */
    public static function statusColor($status)
    {
        return match($status) {
            'draft' => 'bg-gray-700 text-gray-200',
            'submitted' => 'bg-blue-700 text-blue-200',
            'accepted' => 'bg-green-700 text-green-200',
            'need_revision' => 'bg-red-600 text-orange-200',
            default => 'bg-red-700 text-red-200', // Untuk status tidak dikenal
        };
    }
}