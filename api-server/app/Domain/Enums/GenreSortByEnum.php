<?php

namespace App\Domain\Enums;

enum GenreSortByEnum: string
{
    case ID = 'id';
    case NAME = 'name';
    case CREATED_BY = 'created_by';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
}
