<?php

namespace App\Domain\Singers;

use Carbon\CarbonImmutable;

class SingerModel
{
    public function __construct(
        public int $id,
        public string $firstName,
        public ?string $lastName,
        public ?int $age,
        public int $createdBy,
        public CarbonImmutable $createdAt,
        public ?CarbonImmutable $updatedAt
    ) {}
}
