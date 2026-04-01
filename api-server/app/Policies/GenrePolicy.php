<?php

namespace App\Policies;

use App\Models\Genre;
use App\Models\User;

class GenrePolicy
{
    public function view(User $user, Genre $genre): bool
    {
        return $this->canAccess($user, $genre);
    }

    public function update(User $user, Genre $genre): bool
    {
        return $this->canAccess($user, $genre);
    }

    public function delete(User $user, Genre $genre): bool
    {
        return $this->canAccess($user, $genre);
    }

    private function canAccess(User $user, Genre $genre): bool
    {
        return $user->isAdmin() || $genre->created_by === $user->id;
    }
}
