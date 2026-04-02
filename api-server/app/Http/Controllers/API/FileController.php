<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Files\FileIndexRequest;
use App\Http\Requests\Files\FileStoreRequest;
use App\Http\Requests\Files\FileUpdateRequest;
use App\Mappers\FileMapper;
use App\Mappers\PaginationMapper;
use App\Models\File;
use App\Models\Song;
use App\Services\FileServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FileController extends BaseController
{
    public function __construct(private readonly FileServiceInterface $fileService)
    {
    }

    public function index(FileIndexRequest $request): JsonResponse
    {
        $page = $this->fileService->getList(
            PaginationMapper::mapFromValidated($request->validated()),
            $this->listScopeUserId($request->user()),
        );
        $page->items = FileMapper::mapFromListDB($page->items);

        return $this->sendResponse($page, 'Files retrieved successfully.');
    }

    public function store(FileStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $songId = $validated['song_id'] ?? null;

        if ($songId !== null) {
            $song = Song::query()->findOrFail($songId);
            Gate::authorize('update', $song);
        }

        $file = $this->fileService->store(
            $request->file('file'),
            $request->user()->id,
            $validated['description'] ?? null,
            $songId,
        );

        return $this->sendResponse(FileMapper::mapFromDb($file), 'File uploaded successfully.');
    }

    public function show(File $file): JsonResponse
    {
        return $this->sendResponse(FileMapper::mapFromDb($file), 'File retrieved successfully.');
    }

    public function download(int $id)
    {
        $file = $this->fileService->findOrFail($id);
        return Storage::download($file->path);
    }

    public function update(FileUpdateRequest $request, File $file): JsonResponse
    {
        $validated = $request->validated();

        if (array_key_exists('song_id', $validated) && $validated['song_id'] !== null) {
            $song = Song::query()->findOrFail($validated['song_id']);
            Gate::authorize('update', $song);
        }

        $updated = $this->fileService->update((int)$file->id, $validated);

        return $this->sendResponse(FileMapper::mapFromDb($updated), 'File updated successfully.');
    }

    public function destroy(File $file): JsonResponse
    {
        $this->fileService->delete((int)$file->id);

        return $this->sendResponse([], 'File deleted successfully.');
    }
}
