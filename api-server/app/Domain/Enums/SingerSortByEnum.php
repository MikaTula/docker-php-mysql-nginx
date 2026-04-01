<?php

namespace App\Domain\Enums;

enum SingerSortByEnum: string
{
    case ID = 'id';
    case FIRST_NAME = 'first_name';
    case LAST_NAME = 'last_name';
    case AGE = 'age';
    case CREATED_BY = 'created_by';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
}
