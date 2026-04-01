<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Genres\GenreIndexRequest;
use App\Http\Requests\Genres\GenreStoreRequest;
use App\Http\Requests\Genres\GenreUpdateRequest;
use App\Mappers\GenreMapper;
use App\Mappers\PaginationMapper;
use App\Models\Genre;
use App\Services\GenreServiceInterface;
use Illuminate\Http\JsonResponse;

class GenreController extends BaseController
{
    public function __construct(private readonly GenreServiceInterface $genreService) {}

    public function index(GenreIndexRequest $request): JsonResponse
    {
        $page = $this->genreService->getList(
            PaginationMapper::mapFromValidated($request->validated()),
            $this->listScopeUserId($request->user()),
        );
        $page->items = GenreMapper::mapFromListDB($page->items);

        return $this->sendResponse($page, 'Genres retrieved successfully.');
    }

    public function store(GenreStoreRequest $request): JsonResponse
    {
        $genre = $this->genreService->create($request->validated(), $request->user()->id);

        return $this->sendResponse(GenreMapper::mapFromDb($genre), 'Genre created successfully.');
    }

    public function show(Genre $genre): JsonResponse
    {
        return $this->sendResponse(GenreMapper::mapFromDb($genre), 'Genre retrieved successfully.');
    }

    public function update(GenreUpdateRequest $request, Genre $genre): JsonResponse
    {
        $updated = $this->genreService->update((int) $genre->id, $request->validated());

        return $this->sendResponse(GenreMapper::mapFromDb($updated), 'Genre updated successfully.');
    }

    public function destroy(Genre $genre): JsonResponse
    {
        $this->genreService->delete((int) $genre->id);

        return $this->sendResponse([], 'Genre deleted successfully.');
    }
}
