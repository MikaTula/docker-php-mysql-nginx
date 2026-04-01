<?php

namespace App\Repositories;

use App\Domain\Enums\SingerSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Models\Singer;

class SingerRepository implements SingerRepositoryInterface
{
    public function getList(
        int $page,
        int $size,
        SingerSortByEnum $sortBy,
        SortOrderEnum $sortOrder,
    ): iterable {
        return Singer::query()
            ->take($size)
            ->skip(($page - 1) * $size)
            ->orderBy($sortBy->value, $sortOrder->value)
            ->get();
    }

    public function getCount(): int
    {
        return Singer::query()->count();
    }

    public function create(array $attributes): Singer
    {
        return Singer::query()->create($attributes);
    }

    public function findOrFail(int $id): Singer
    {
        return Singer::query()->findOrFail($id);
    }

    public function update(int $id, array $attributes): Singer
    {
        $singer = $this->findOrFail($id);
        $singer->fill($attributes);
        $singer->save();

        return $singer;
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id)->delete();
    }
}
