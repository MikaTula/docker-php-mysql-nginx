<?php

namespace App\Services;

use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Models\Singer;
use Illuminate\Container\Attributes\Bind;

#[Bind(SingerService::class)]
interface SingerServiceInterface
{
    public function getList(PaginationInModel $paginationInModel, ?int $scopedToUserId = null): PaginationOutModel;

    public function create(array $attributes, int $createdBy): Singer;

    public function findOrFail(int $id): Singer;

    public function update(int $id, array $attributes): Singer;

    public function delete(int $id): void;
}
