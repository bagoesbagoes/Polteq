<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    // Hanya reviewer yang membuat atau dosen/admin bisa view
    public function view(User $user, Review $review): bool
    {
        return $user->id === $review->reviewer_id || in_array($user->role, ['dosen', 'admin']);
    }

    // Hanya reviewer yang membuat bisa update
    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->reviewer_id;
    }

    // Hanya reviewer yang membuat bisa delete
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->reviewer_id;
    }
}