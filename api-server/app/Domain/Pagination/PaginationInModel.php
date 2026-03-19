<?php

namespace App\Domain\Pagination;

class PaginationInModel
{
    public int $page;
    public int $size;
    public string $sortBy;
    public string $sortOrder;

    public function __construct(
        int $page,
        int $size,
        string $sortBy,
        string $sortOrder
    ) {
        $this->page = $page;
        $this->size = $size;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
    }
}
