<?php

namespace App\Services;

use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use Illuminate\Container\Attributes\Bind;

#[Bind(SongService::class)]
interface SongServiceInterface
{
    /**
     * @param PaginationInModel $paginationInModel
     * @return PaginationOutModel
     */
    public function getList(PaginationInModel $paginationInModel): PaginationOutModel;
}
