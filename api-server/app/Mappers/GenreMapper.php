<?php

namespace App\Mappers;

use App\Domain\Genres\GenreModel;
use App\Models\Genre;
use Carbon\CarbonImmutable;

class GenreMapper
{
    /**
     * @return GenreModel[]
     */
    public static function mapFromListDB(iterable $genres): array
    {
        $res = [];
        foreach ($genres as $genre) {
            $res[] = self::mapFromDb($genre);
        }

        return $res;
    }

    public static function mapFromDb(Genre $genre): GenreModel
    {
        return new GenreModel(
            $genre->id,
            $genre->name,
            $genre->created_by,
            CarbonImmutable::parse($genre->created_at),
            $genre->updated_at ? CarbonImmutable::parse($genre->updated_at) : null,
        );
    }
}
