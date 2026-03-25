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

    public function getCount(?int $userId = null): int;

    public function create(array $attributes): Song;

    public function findOrFail(int $id): Song;

    public function update(int $id, array $attributes): Song;

    public function delete(int $id): void;
}
