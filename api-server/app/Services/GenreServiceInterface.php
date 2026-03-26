<?php

namespace App\Services;

use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Models\Genre;
use Illuminate\Container\Attributes\Bind;

#[Bind(GenreService::class)]
interface GenreServiceInterface
{
    public function getList(PaginationInModel $paginationInModel, ?int $scopedToUserId = null): PaginationOutModel;

    public function create(array $attributes, int $createdBy): Genre;

    public function findOrFail(int $id): Genre;

    public function update(int $id, array $attributes): Genre;

    public function delete(int $id): void;
}
