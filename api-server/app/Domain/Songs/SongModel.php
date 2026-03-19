<?php

namespace App\Domain\Songs;

use App\Models\Singer;
use Carbon\CarbonImmutable;

class SongModel
{
    public function __construct(
        public int $id,
        public string $name,
        public IdNameModel $singer,
        public int $year,
        public CarbonImmutable $createdAt,
        public ?CarbonImmutable $updatedAt
    ) {}
}
