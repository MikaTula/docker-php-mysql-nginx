<?php

namespace App\Services;

use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Models\File;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Http\UploadedFile;

#[Bind(FileService::class)]
interface FileServiceInterface
{
    public function getList(PaginationInModel $paginationInModel, ?int $scopedToUserId = null): PaginationOutModel;

    public function store(UploadedFile $uploaded, int $userId, ?string $description, ?int $songId): File;

    public function findOrFail(int $id): File;

    public function update(int $id, array $attributes): File;

    public function delete(int $id): void;

    /**
     * Удаляет файл, привязанный к песне (если есть), с диска и из БД.
     */
    public function deleteLinkedFilesForSong(int $songId): void;
}
