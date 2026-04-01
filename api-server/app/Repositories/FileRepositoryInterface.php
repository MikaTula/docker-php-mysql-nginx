<?php

namespace App\Repositories;

use App\Domain\Enums\FileSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Models\File;
use Illuminate\Container\Attributes\Bind;

#[Bind(FileRepository::class)]
interface FileRepositoryInterface
{
    /**
     * @return iterable<int, File>
     */
    public function getList(
        int $page,
        int $size,
        FileSortByEnum $sortBy,
        SortOrderEnum $sortOrder,
        ?int $userId = null,
    ): iterable;

    public function getCount(?int $userId = null): int;

    public function create(array $attributes): File;

    public function findOrFail(int $id): File;

    public function update(int $id, array $attributes): File;
}
