<?php

namespace App\Repositories;

use App\Domain\Enums\SongSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Models\Song;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Support\Collection;

#[Bind(SongRepository::class)]
interface SongRepositoryInterface
{
    /**
     * @return Collection<Song>
     */
    public function getList(
        int $page,
        int $size,
        SongSortByEnum $sortBy,
        SortOrderEnum $sortOrder,
        ?int $userId = null,
    ): iterable;


    /**
     * @param int|null $userId
     * @return int
     */
    public function getCount(?int $userId = null): int;
}
