<?php

namespace App\Domain\Enums;

enum FileSortByEnum: string
{
    case ID = 'id';
    case USER_ID = 'user_id';
    case ORIGINAL_NAME = 'original_name';
    case MIME_TYPE = 'mime_type';
    case SIZE = 'size';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
}
