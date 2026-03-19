<?php

namespace App\Mappers;

use App\Domain\Songs\IdNameModel;
use App\Models\Singer;
use JsonMapper_Exception;

class SingerMapper
{
    /**
     * @throws JsonMapper_Exception
     */
    public static function mapFromDbToIdName(Singer $singer): IdNameModel
    {
        return new IdNameModel($singer->id, $singer->fullName);
    }


}

