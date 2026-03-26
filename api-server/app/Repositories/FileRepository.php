<?php

namespace App\Repositories;

use App\Domain\Enums\FileSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Models\File;

class FileRepository implements FileRepositoryInterface
{
    public function getList(
        int $page,
        int $size,
        FileSortByEnum $sortBy,
        SortOrderEnum $sortOrder,
        ?int $userId = null,
    ): iterable {
        $query = File::query()->with('song');
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        return $query
            ->take($size)
            ->skip(($page - 1) * $size)
            ->orderBy($sortBy->value, $sortOrder->value)
            ->get();
    }

    public function getCount(?int $userId = null): int
    {
        $query = File::query();
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        return $query->count();
    }

    public function create(array $attributes): File
    {
        return File::query()->create($attributes);
    }

    public function findOrFail(int $id): File
    {
        return File::query()->findOrFail($id);
    }

    public function update(int $id, array $attributes): File
    {
        $file = $this->findOrFail($id);
        $file->fill($attributes);
        $file->save();

        return $file;
    }
}
