<?php

namespace App\Services;

use App\Domain\Enums\SongSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Repositories\SongRepositoryInterface;

readonly class SongService implements SongServiceInterface
{
    public function __construct(private SongRepositoryInterface $songRepository) {}

    public function getList(PaginationInModel $paginationInModel): PaginationOutModel
    {
        $songs = $this->songRepository->getList(
            $paginationInModel->page,
            $paginationInModel->size,
            SongSortByEnum::from($paginationInModel->sortBy),
            SortOrderEnum::from($paginationInModel->sortOrder)
        );

        $count = $this->songRepository->getCount();

        return new PaginationOutModel($songs, $paginationInModel->page, $paginationInModel->size, $count);
    }
}
