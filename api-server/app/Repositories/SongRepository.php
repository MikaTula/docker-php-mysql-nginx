<?php

namespace App\Repositories;

use App\Domain\Enums\SongSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Models\Song;

class SongRepository implements SongRepositoryInterface
{
    /**
     * @return Song[]
     */
    public function getList(
        int $page,
        int $size,
        SongSortByEnum $sortBy,
        SortOrderEnum $sortOrder,
        ?int $userId = null
    ): iterable {
        $query = Song::query()->with('singer');
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
        $query = Song::query();
        if ($userId !== null) {
            $query->where('created_by', $userId);
        }

        return $query->count();
    }

    public function create(array $attributes): Song
    {
        return Song::query()->create($attributes);
    }

    public function findOrFail(int $id): Song
    {
        return Song::query()->findOrFail($id);
    }

    public function update(int $id, array $attributes): Song
    {
        $song = $this->findOrFail($id);
        $song->fill($attributes);
        $song->save();

        return $song;
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id)->delete();
    }
}
