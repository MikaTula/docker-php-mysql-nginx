<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Genres\GenreIndexRequest;
use App\Http\Requests\Genres\GenreStoreRequest;
use App\Http\Requests\Genres\GenreUpdateRequest;
use App\Http\Resources\GenreResource;
use App\Mappers\GenreMapper;
use App\Mappers\PaginationMapper;
use App\Services\GenreServiceInterface;
use Illuminate\Http\JsonResponse;

class GenreController extends BaseController
{
    public function __construct(private readonly GenreServiceInterface $genreService) {}

    public function index(GenreIndexRequest $request): JsonResponse
    {
        $page = $this->genreService->getList(PaginationMapper::mapFromValidated($request->validated()));
        $page->items = GenreMapper::mapFromListDB($page->items);

        return $this->sendResponse($page, 'Genres retrieved successfully.');
    }

    public function store(GenreStoreRequest $request): JsonResponse
    {
        $genre = $this->genreService->create($request->validated(), $request->user()->id);

        return $this->sendResponse(new GenreResource($genre), 'Genre created successfully.');
    }

    public function show($genre): JsonResponse
    {
        $genre = $this->genreService->findOrFail((int) $genre);

        return $this->sendResponse(new GenreResource($genre), 'Genre retrieved successfully.');
    }

    public function update(GenreUpdateRequest $request, $genre): JsonResponse
    {
        $genre = $this->genreService->update((int) $genre, $request->validated());

        return $this->sendResponse(new GenreResource($genre), 'Genre updated successfully.');
    }

    public function destroy($genre): JsonResponse
    {
        $this->genreService->delete((int) $genre);

        return $this->sendResponse([], 'Genre deleted successfully.');
    }
}
