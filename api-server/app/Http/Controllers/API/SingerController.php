<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Singers\SingerIndexRequest;
use App\Http\Requests\Singers\SingerStoreRequest;
use App\Http\Requests\Singers\SingerUpdateRequest;
use App\Http\Resources\SingerResource;
use App\Mappers\PaginationMapper;
use App\Mappers\SingerMapper;
use App\Services\SingerServiceInterface;
use Illuminate\Http\JsonResponse;

class SingerController extends BaseController
{
    public function __construct(private readonly SingerServiceInterface $singerService) {}

    public function index(SingerIndexRequest $request): JsonResponse
    {
        $page = $this->singerService->getList(PaginationMapper::mapFromValidated($request->validated()));
        $page->items = SingerMapper::mapFromListDB($page->items);

        return $this->sendResponse($page, 'Singers retrieved successfully.');
    }

    public function store(SingerStoreRequest $request): JsonResponse
    {
        $singer = $this->singerService->create($request->validated(), $request->user()->id);

        return $this->sendResponse(new SingerResource($singer), 'Singer created successfully.');
    }

    public function show($singer): JsonResponse
    {
        $singer = $this->singerService->findOrFail((int) $singer);

        return $this->sendResponse(new SingerResource($singer), 'Singer retrieved successfully.');
    }

    public function update(SingerUpdateRequest $request, $singer): JsonResponse
    {
        $singer = $this->singerService->update((int) $singer, $request->validated());

        return $this->sendResponse(new SingerResource($singer), 'Singer updated successfully.');
    }

    public function destroy($singer): JsonResponse
    {
        $this->singerService->delete((int) $singer);

        return $this->sendResponse([], 'Singer deleted successfully.');
    }
}
