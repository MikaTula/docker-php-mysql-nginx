<?php

namespace App\Mappers;

use App\Domain\Files\FileModel;
use App\Models\File;
use Carbon\CarbonImmutable;

class FileMapper
{
    /**
     * @return FileModel[]
     */
    public static function mapFromListDB(iterable $files): array
    {
        $res = [];
        foreach ($files as $file) {
            $res[] = self::mapFromDb($file);
        }

        return $res;
    }

    public static function mapFromDb(File $file): FileModel
    {
        $file->loadMissing('song');

        return new FileModel(
            $file->id,
            $file->user_id,
            $file->description,
            $file->original_name,
            $file->mime_type,
            $file->size,
            $file->song?->id,
            CarbonImmutable::parse($file->created_at),
            $file->updated_at !== null ? CarbonImmutable::parse($file->updated_at) : null,
        );
    }
}
