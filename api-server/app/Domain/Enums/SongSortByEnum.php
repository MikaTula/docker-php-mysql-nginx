<?php

namespace App\Domain\Enums;

enum SongSortByEnum: string
{
    case ID = 'id';
    case NAME = 'name';
    case DESC = 'desc';
    case SINGER_ID = 'singer_id';
    case YEAR = 'year';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';

}
