<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Singers\SingerIndexRequest;
use App\Http\Requests\Singers\SingerStoreRequest;
use App\Http\Requests\Singers\SingerUpdateRequest;
use App\Mappers\PaginationMapper;
use App\Mappers\SingerMapper;
use App\Models\Singer;
use App\Services\SingerServiceInterface;
use Illuminate\Http\JsonResponse;

class SingerController extends BaseController
{
    public function __construct(private readonly SingerServiceInterface $singerService) {}

    public function index(SingerIndexRequest $request): JsonResponse
    {
        $page = $this->singerService->getList(
            PaginationMapper::mapFromValidated($request->validated()),
            $this->listScopeUserId($request->user()),
        );
        $page->items = SingerMapper::mapFromListDB($page->items);

        return $this->sendResponse($page, 'Singers retrieved successfully.');
    }

    public function store(SingerStoreRequest $request): JsonResponse
    {
        $singer = $this->singerService->create($request->validated(), $request->user()->id);

        return $this->sendResponse(SingerMapper::mapFromDb($singer), 'Singer created successfully.');
    }

    public function show(Singer $singer): JsonResponse
    {
        return $this->sendResponse(SingerMapper::mapFromDb($singer), 'Singer retrieved successfully.');
    }

    public function update(SingerUpdateRequest $request, Singer $singer): JsonResponse
    {
        $updated = $this->singerService->update((int) $singer->id, $request->validated());

        return $this->sendResponse(SingerMapper::mapFromDb($updated), 'Singer updated successfully.');
    }

    public function destroy(Singer $singer): JsonResponse
    {
        $this->singerService->delete((int) $singer->id);

        return $this->sendResponse([], 'Singer deleted successfully.');
    }
}
