<?php

namespace App\Policies;

use App\Models\Reel;
use App\Models\User;

class ReelPolicy
{
    public function delete(User $user, Reel $reel): bool
    {
        return $user->id === $reel->user_id || $user->isAdmin();
    }

    public function update(User $user, Reel $reel): bool
    {
        return $user->id === $reel->user_id;
    }
}
