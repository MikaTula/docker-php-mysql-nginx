<?php

namespace App\Policies;

use App\Models\Song;
use App\Models\User;

class SongPolicy
{
    public function view(User $user, Song $song): bool
    {
        return $this->canAccess($user, $song);
    }

    public function update(User $user, Song $song): bool
    {
        return $this->canAccess($user, $song);
    }

    public function delete(User $user, Song $song): bool
    {
        return $this->canAccess($user, $song);
    }

    private function canAccess(User $user, Song $song): bool
    {
        return $user->isAdmin() || $song->created_by === $user->id;
    }
}
