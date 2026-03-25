<?php

namespace App\Domain\Genres;

use Carbon\CarbonImmutable;

class GenreModel
{
    public function __construct(
        public int $id,
        public string $name,
        public int $createdBy,
        public CarbonImmutable $createdAt,
        public ?CarbonImmutable $updatedAt
    ) {}
}
