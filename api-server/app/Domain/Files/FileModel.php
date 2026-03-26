<?php

namespace App\Domain\Files;

use Carbon\CarbonImmutable;

class FileModel
{
    public function __construct(
        public int $id,
        public int $userId,
        public ?string $description,
        public string $originalName,
        public ?string $mimeType,
        public int $size,
        public ?int $songId,
        public CarbonImmutable $createdAt,
        public ?CarbonImmutable $updatedAt,
    ) {}
}
