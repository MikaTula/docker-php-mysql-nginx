<?php

namespace App\Policies;

use App\Models\Singer;
use App\Models\User;

class SingerPolicy
{
    public function view(User $user, Singer $singer): bool
    {
        return $this->canAccess($user, $singer);
    }

    public function update(User $user, Singer $singer): bool
    {
        return $this->canAccess($user, $singer);
    }

    public function delete(User $user, Singer $singer): bool
    {
        return $this->canAccess($user, $singer);
    }

    private function canAccess(User $user, Singer $singer): bool
    {
        return $user->isAdmin() || $singer->created_by === $user->id;
    }
}
