<?php

namespace App\Services;

use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Models\Song;

interface SongServiceInterface
{
    public function getList(PaginationInModel $paginationInModel, ?int $scopedToUserId = null): PaginationOutModel;

    public function create(array $attributes, int $createdBy): Song;

    public function findOrFail(int $id): Song;

    public function update(int $id, array $attributes): Song;

    public function delete(int $id): void;
}
