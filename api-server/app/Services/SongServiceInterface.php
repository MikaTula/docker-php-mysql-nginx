<?php

namespace App\Services;

use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;

interface SongServiceInterface
{
    public function getList(PaginationInModel $paginationInModel, ?int $scopedToUserId = null): PaginationOutModel;

    public function create(array $attributes, int $createdBy): \App\Models\Song;

    public function findOrFail(int $id): \App\Models\Song;

    public function update(int $id, array $attributes): \App\Models\Song;

    public function delete(int $id): void;
}
