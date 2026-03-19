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
        return Song::query()
            ->take($size)
            ->skip(($page - 1) * $size)
            ->orderBy($sortBy->value, $sortOrder->value)
            ->get();
    }

    /**
     * @param int|null $userId
     * @return int
     */
    public function getCount(?int $userId = null): int
    {
        return Song::query()->count();
    }
}
