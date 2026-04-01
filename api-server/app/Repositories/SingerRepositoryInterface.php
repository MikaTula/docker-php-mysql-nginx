<?php

namespace App\Repositories;

use App\Domain\Enums\SingerSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Models\Singer;
use Illuminate\Container\Attributes\Bind;

#[Bind(SingerRepository::class)]
interface SingerRepositoryInterface
{
    /**
     * @return iterable<int, Singer>
     */
    public function getList(
        int $page,
        int $size,
        SingerSortByEnum $sortBy,
        SortOrderEnum $sortOrder,
        ?int $userId = null,
    ): iterable;

    public function getCount(?int $userId = null): int;

    public function create(array $attributes): Singer;

    public function findOrFail(int $id): Singer;

    public function update(int $id, array $attributes): Singer;

    public function delete(int $id): void;
}
