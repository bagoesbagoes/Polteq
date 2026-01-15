<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;

class ProposalPolicy
{
    /**
     * Determine if the user can view the proposal
     */
    public function view(User $user, Proposal $proposal): bool
    {
        // Owner, reviewer, atau admin bisa lihat
        return $user->id === $proposal->user_id || in_array($user->role, ['reviewer', 'admin']);
    }

    /**
     * Determine if the user can update the proposal
     */
    public function update(User $user, Proposal $proposal): bool
    {
        // Hanya owner yang bisa update
        if ($user->id !== $proposal->user_id) {
            return false;
        }

        // Hanya draft dan need_revision yang boleh diedit
        return in_array($proposal->status, ['draft', 'need_revision']);
    }

    /**
     * Determine if the user can delete the proposal
     */
    public function delete(User $user, Proposal $proposal): bool
    {
        // Hanya owner yang bisa delete DAN hanya yang masih draft
        return $user->id === $proposal->user_id && $proposal->status === 'draft';
    }

    /**
     * Determine if the user can review the proposal
     */
    public function review(User $user, Proposal $proposal): bool
    {
        // Hanya reviewer yang bisa review
        if ($user->role !== 'reviewer') {
            return false;
        }

        // Hanya proposal dengan status 'submitted' yang bisa direview
        return $proposal->status === 'submitted';
    }
}