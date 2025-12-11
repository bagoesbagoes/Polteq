<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;

class ProposalPolicy
{
    public function view(User $user, Proposal $proposal): bool
    {
        return $user->id === $proposal->user_id || in_array($user->role, ['dosen', 'admin']);
    }

    public function update(User $user, Proposal $proposal): bool
    {
        return $user->id === $proposal->user_id;
    }

    public function delete(User $user, Proposal $proposal): bool
    {
        return $user->id === $proposal->user_id;
    }
}