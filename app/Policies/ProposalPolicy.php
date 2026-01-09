<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;

class ProposalPolicy
{
    public function view(User $user, Proposal $proposal): bool
    {
        // Owner, reviewer, atau admin bisa lihat
        return $user->id === $proposal->user_id || in_array($user->role, ['reviewer', 'admin']);
    }

    public function update(User $user, Proposal $proposal): bool
    {
        // Hanya owner yang bisa update
        if ($user->id !== $proposal->user_id) {
            return false;
        }

        // Hanya draft dan need_revision yang boleh diedit
        $editableStatuses = ['draft', 'need_revision'];
        
        return in_array($proposal->status, $editableStatuses);
    }

    public function delete(User $user, Proposal $proposal): bool
    {
        // Hanya owner yang bisa delete DAN hanya yang masih draft
        return $user->id === $proposal->user_id && $proposal->status === 'draft';
    }

    public function review(User $user, Proposal $proposal): bool
    {
        // Hanya reviewer yang bisa review
        if ($user->role !== 'reviewer') {
            return false;
        }

        // Hanya proposal dengan status submitted atau under_review yang bisa direview
        return in_array($proposal->status, ['submitted', 'under_review']);
    }
}