<?php

namespace App\Repositories;

use App\Domain\Enums\GenreSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Models\Genre;
use Illuminate\Container\Attributes\Bind;

#[Bind(GenreRepository::class)]
interface GenreRepositoryInterface
{
    /**
     * @return iterable<int, Genre>
     */
    public function getList(
        int $page,
        int $size,
        GenreSortByEnum $sortBy,
        SortOrderEnum $sortOrder,
    ): iterable;

    public function getCount(): int;

    public function create(array $attributes): Genre;

    public function findOrFail(int $id): Genre;

    public function update(int $id, array $attributes): Genre;

    public function delete(int $id): void;
}
