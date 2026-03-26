<?php

namespace App\Services;

use App\Domain\Enums\SingerSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Models\Singer;
use App\Repositories\SingerRepositoryInterface;

readonly class SingerService implements SingerServiceInterface
{
    public function __construct(private SingerRepositoryInterface $singerRepository) {}

    public function getList(PaginationInModel $paginationInModel, ?int $scopedToUserId = null): PaginationOutModel
    {
        $items = $this->singerRepository->getList(
            $paginationInModel->page,
            $paginationInModel->size,
            SingerSortByEnum::from($paginationInModel->sortBy),
            SortOrderEnum::from($paginationInModel->sortOrder),
            $scopedToUserId,
        );

        $count = $this->singerRepository->getCount($scopedToUserId);

        return new PaginationOutModel($items, $paginationInModel->page, $paginationInModel->size, $count);
    }

    public function create(array $attributes, int $createdBy): Singer
    {
        return $this->singerRepository->create([
            ...$attributes,
            'created_by' => $createdBy,
        ]);
    }

    public function findOrFail(int $id): Singer
    {
        return $this->singerRepository->findOrFail($id);
    }

    public function update(int $id, array $attributes): Singer
    {
        return $this->singerRepository->update($id, $attributes);
    }

    public function delete(int $id): void
    {
        $this->singerRepository->delete($id);
    }
}
