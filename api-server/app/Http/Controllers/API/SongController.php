<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Common\PaginationRequest;
use App\Http\Requests\Songs\SongStoreRequest;
use App\Http\Requests\Songs\SongUpdateRequest;
use App\Mappers\PaginationMapper;
use App\Mappers\SongMapper;
use App\Models\Song;
use App\Services\SongServiceInterface;
use Illuminate\Http\JsonResponse;

class SongController extends BaseController
{
    public function __construct(private readonly SongServiceInterface $songService) {}

    public function index(PaginationRequest $request): JsonResponse
    {
        $songsPage = $this->songService->getList(
            PaginationMapper::mapFromRequest($request),
            $this->listScopeUserId($request->user()),
        );
        $songsPage->items = SongMapper::mapFromListDB($songsPage->items);

        return $this->sendResponse(
            $songsPage,
            'Songs retrieved successfully.'
        );
    }

    // Store a newly created resource in storage.

    public function store(SongStoreRequest $request): JsonResponse
    {
        $song = $this->songService->create($request->validated(), $request->user()->id);

        return $this->sendResponse(SongMapper::mapFromDb($song), 'Song created successfully.');
    }

    // Display the specified resource.
    public function show(Song $song): JsonResponse
    {
        $song->loadMissing('singer');

        return $this->sendResponse(SongMapper::mapFromDb($song), 'Song retrieved successfully.');
    }

    // Update the specified resource in storage.
    public function update(SongUpdateRequest $request, Song $song): JsonResponse
    {
        $updated = $this->songService->update($song->id, $request->validated());

        return $this->sendResponse(SongMapper::mapFromDb($updated), 'Song updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy(Song $song): JsonResponse
    {
        $this->songService->delete($song->id);

        return $this->sendResponse([], 'Song deleted successfully.');
    }
}
