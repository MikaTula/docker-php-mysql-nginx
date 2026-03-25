<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Common\PaginationRequest;
use App\Http\Requests\Songs\SongStoreRequest;
use App\Http\Requests\Songs\SongUpdateRequest;
use App\Http\Resources\SongResource;
use App\Mappers\PaginationMapper;
use App\Mappers\SongMapper;
use App\Services\SongServiceInterface;
use Illuminate\Http\JsonResponse;

class SongController extends BaseController
{
    public function __construct(private readonly SongServiceInterface $songService) {}

    public function index(PaginationRequest $request): JsonResponse
    {
        $songsPage = $this->songService->getList(PaginationMapper::mapFromRequest($request));
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

        return $this->sendResponse(new SongResource($song), 'Song created successfully.');
    }

    // Display the specified resource.
    public function show($song): JsonResponse
    {
        $song = $this->songService->findOrFail((int) $song);

        return $this->sendResponse(new SongResource($song), 'Song retrieved successfully.');
    }

    // Update the specified resource in storage.
    public function update(SongUpdateRequest $request, $song): JsonResponse
    {
        $song = $this->songService->update((int) $song, $request->validated());

        return $this->sendResponse(new SongResource($song), 'Song updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy($song): JsonResponse
    {
        $this->songService->delete((int) $song);

        return $this->sendResponse([], 'Song deleted successfully.');
    }
}
