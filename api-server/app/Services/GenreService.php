<?php

namespace App\Services;

use App\Domain\Enums\GenreSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Models\Genre;
use App\Repositories\GenreRepositoryInterface;

readonly class GenreService implements GenreServiceInterface
{
    public function __construct(private GenreRepositoryInterface $genreRepository) {}

    public function getList(PaginationInModel $paginationInModel): PaginationOutModel
    {
        $items = $this->genreRepository->getList(
            $paginationInModel->page,
            $paginationInModel->size,
            GenreSortByEnum::from($paginationInModel->sortBy),
            SortOrderEnum::from($paginationInModel->sortOrder)
        );

        $count = $this->genreRepository->getCount();

        return new PaginationOutModel($items, $paginationInModel->page, $paginationInModel->size, $count);
    }

    public function create(array $attributes, int $createdBy): Genre
    {
        return $this->genreRepository->create([
            ...$attributes,
            'created_by' => $createdBy,
        ]);
    }

    public function findOrFail(int $id): Genre
    {
        return $this->genreRepository->findOrFail($id);
    }

    public function update(int $id, array $attributes): Genre
    {
        return $this->genreRepository->update($id, $attributes);
    }

    public function delete(int $id): void
    {
        $this->genreRepository->delete($id);
    }
}
