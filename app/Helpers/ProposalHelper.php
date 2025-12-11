<?php

namespace App\Helpers;

class ProposalHelper
{
    public static function statusColor($status)
    {
        return match($status) {
            'draft' => 'bg-gray-700 text-gray-200',
            'submitted' => 'bg-blue-700 text-blue-200',
            'under_review' => 'bg-yellow-700 text-yellow-200',
            'accepted' => 'bg-green-700 text-green-200',
            default => 'bg-red-700 text-red-200',
        };
    }
}