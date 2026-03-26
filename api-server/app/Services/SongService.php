<?php

namespace App\Services;

use App\Domain\Enums\SongSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Models\Song;
use App\Repositories\SongRepositoryInterface;

readonly class SongService implements SongServiceInterface
{
    public function __construct(
        private SongRepositoryInterface $songRepository,
        private FileServiceInterface $fileService,
    ) {}

    public function getList(PaginationInModel $paginationInModel, ?int $scopedToUserId = null): PaginationOutModel
    {
        $songs = $this->songRepository->getList(
            $paginationInModel->page,
            $paginationInModel->size,
            SongSortByEnum::from($paginationInModel->sortBy),
            SortOrderEnum::from($paginationInModel->sortOrder),
            $scopedToUserId,
        );

        $count = $this->songRepository->getCount($scopedToUserId);

        return new PaginationOutModel($songs, $paginationInModel->page, $paginationInModel->size, $count);
    }

    public function create(array $attributes, int $createdBy): Song
    {
        return $this->songRepository->create([
            ...$attributes,
            'created_by' => $createdBy,
        ]);
    }

    public function findOrFail(int $id): Song
    {
        return $this->songRepository->findOrFail($id);
    }

    public function update(int $id, array $attributes): Song
    {
        return $this->songRepository->update($id, $attributes);
    }

    public function delete(int $id): void
    {
        $this->fileService->deleteLinkedFilesForSong($id);
        $this->songRepository->delete($id);
    }
}
