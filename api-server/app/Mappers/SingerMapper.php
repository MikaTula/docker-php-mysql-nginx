<?php

namespace App\Mappers;

use App\Domain\Singers\SingerModel;
use App\Domain\Songs\IdNameModel;
use App\Models\Singer;
use Carbon\CarbonImmutable;

class SingerMapper
{
    /**
     * @return SingerModel[]
     */
    public static function mapFromListDB(iterable $singers): array
    {
        $res = [];
        foreach ($singers as $singer) {
            $res[] = self::mapFromDb($singer);
        }

        return $res;
    }

    public static function mapFromDb(Singer $singer): SingerModel
    {
        return new SingerModel(
            $singer->id,
            $singer->first_name,
            $singer->last_name,
            $singer->age,
            $singer->created_by,
            CarbonImmutable::parse($singer->created_at),
            $singer->updated_at ? CarbonImmutable::parse($singer->updated_at) : null,
        );
    }

    public static function mapFromDbToIdName(Singer $singer): IdNameModel
    {
        return new IdNameModel($singer->id, $singer->fullName);
    }
}
