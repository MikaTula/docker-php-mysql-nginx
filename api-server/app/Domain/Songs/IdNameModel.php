<?php

namespace App\Domain\Songs;

class IdNameModel
{
    public function __construct(
        public int $id,
        public string $name
    ) {}

}
