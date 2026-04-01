<?php

namespace App\Repositories;

use App\Domain\Enums\GenreSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Models\Genre;

class GenreRepository implements GenreRepositoryInterface
{
    public function getList(
        int $page,
        int $size,
        GenreSortByEnum $sortBy,
        SortOrderEnum $sortOrder,
        ?int $userId = null,
    ): iterable {
        $query = Genre::query();
        if ($userId !== null) {
            $query->where('created_by', $userId);
        }

        return $query
            ->take($size)
            ->skip(($page - 1) * $size)
            ->orderBy($sortBy->value, $sortOrder->value)
            ->get();
    }

    public function getCount(?int $userId = null): int
    {
        $query = Genre::query();
        if ($userId !== null) {
            $query->where('created_by', $userId);
        }

        return $query->count();
    }

    public function create(array $attributes): Genre
    {
        return Genre::query()->create($attributes);
    }

    public function findOrFail(int $id): Genre
    {
        return Genre::query()->findOrFail($id);
    }

    public function update(int $id, array $attributes): Genre
    {
        $genre = $this->findOrFail($id);
        $genre->fill($attributes);
        $genre->save();

        return $genre;
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id)->delete();
    }
}
