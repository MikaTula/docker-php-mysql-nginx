<?php

namespace App\Mappers;

use App\Domain\Songs\SongModel;
use App\Models\Song;

class SongMapper
{
    /**
     * @return SongModel[]
     */
    public static function mapFromListDB(iterable $songs): array
    {
        $res = [];
        foreach ($songs as $song) {
            $res[] = SongMapper::mapFromDb($song);
        }

        return $res;
    }

    public static function mapFromDb(Song $song): SongModel
    {
        return new SongModel(
            $song->id,
            $song->name,
            SingerMapper::mapFromDbToIdName($song->singer),
            $song->year,
            $song->created_at,
            $song->updated_at,
            $song->file ? FileMapper::mapFromDb($song->file) : null
        );
    }
}
