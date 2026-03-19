<?php

namespace App\Domain\Pagination;

class PaginationOutModel
{
    public int $page = 1;
    public int $size = 10;
    public int $total = 10;

    public iterable $items;

    public function __construct(
        iterable $items = [],
        int $page = 1,
        int $size = 10,
        int $total = 10,
    ) {
        $this->page = $page;
        $this->size = $size;
        $this->total = $total;
        $this->items = $items;
    }
}
