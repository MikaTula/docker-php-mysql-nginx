<?php

namespace App\Services;

use App\Domain\Enums\FileSortByEnum;
use App\Domain\Enums\SortOrderEnum;
use App\Domain\Pagination\PaginationInModel;
use App\Domain\Pagination\PaginationOutModel;
use App\Models\File;
use App\Models\Song;
use App\Repositories\FileRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

readonly class FileService implements FileServiceInterface
{
    public function __construct(private FileRepositoryInterface $fileRepository) {}

    public function getList(PaginationInModel $paginationInModel, ?int $scopedToUserId = null): PaginationOutModel
    {
        $items = $this->fileRepository->getList(
            $paginationInModel->page,
            $paginationInModel->size,
            FileSortByEnum::from($paginationInModel->sortBy),
            SortOrderEnum::from($paginationInModel->sortOrder),
            $scopedToUserId,
        );

        $count = $this->fileRepository->getCount($scopedToUserId);

        return new PaginationOutModel($items, $paginationInModel->page, $paginationInModel->size, $count);
    }

    public function store(UploadedFile $uploaded, int $userId, ?string $description, ?int $songId): File
    {
        return DB::transaction(function () use ($uploaded, $userId, $description, $songId): File {
            $path = $uploaded->store('uploads/'.$userId, 'local');

            $file = $this->fileRepository->create([
                'user_id' => $userId,
                'description' => $description,
                'disk' => 'local',
                'path' => $path,
                'original_name' => $uploaded->getClientOriginalName(),
                'mime_type' => $uploaded->getClientMimeType(),
                'size' => $uploaded->getSize(),
            ]);

            if ($songId !== null) {
                $this->syncFileToSong($file, $songId);
            }

            return $file->fresh(['song']);
        });
    }

    public function findOrFail(int $id): File
    {
        return $this->fileRepository->findOrFail($id);
    }

    public function update(int $id, array $attributes): File
    {
        return DB::transaction(function () use ($id, $attributes): File {
            $file = $this->fileRepository->findOrFail($id);

            if (array_key_exists('description', $attributes)) {
                $file->description = $attributes['description'];
            }

            $file->save();

            if (array_key_exists('song_id', $attributes)) {
                $this->syncFileToSong($file, $attributes['song_id']);
            }

            return $file->fresh(['song']);
        });
    }

    public function delete(int $id): void
    {
        $file = $this->fileRepository->findOrFail($id);
        $file->deletePhysicalFile();
        $file->delete();
    }

    public function deleteLinkedFilesForSong(int $songId): void
    {
        $song = Song::query()->findOrFail($songId);
        if ($song->file_id === null) {
            return;
        }

        $file = $this->fileRepository->findOrFail($song->file_id);
        $file->deletePhysicalFile();
        $file->delete();
    }

    private function syncFileToSong(File $file, ?int $songId): void
    {
        Song::query()->where('file_id', $file->id)->update(['file_id' => null]);

        if ($songId === null) {
            return;
        }

        $song = Song::query()->findOrFail($songId);

        if ($song->file_id !== null && (int) $song->file_id !== (int) $file->id) {
            $oldFile = File::query()->findOrFail($song->file_id);
            $oldFile->deletePhysicalFile();
            $oldFile->delete();
        }

        $song->update(['file_id' => $file->id]);
    }
}
