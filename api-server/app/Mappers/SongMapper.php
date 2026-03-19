<?php

namespace App\Mappers;

use App\Domain\Songs\SongModel;
use JsonMapper_Exception;

class SongMapper
{
    /**
     * @return SongModel[]
     *
     * @throws JsonMapper_Exception
     */
    public static function mapFromListDB(iterable $songs): array
    {
        $res = [];
        foreach ($songs as $song) {
            $res[] = new SongModel(
                $song->id,
                $song->name,
                SingerMapper::mapFromDbToIdName($song->singer),
                $song->year,
                $song->created_at,
                $song->updated_at
            );
        }

        return $res;
    }
}
