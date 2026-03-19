<?php

namespace App\Domain\Songs;

use Carbon\CarbonImmutable;

class SingerModel
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public int $age,
        public CarbonImmutable $createdAt,
        public ?CarbonImmutable $updatedAt
    ) {
    }


}
