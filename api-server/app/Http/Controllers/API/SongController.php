<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Common\PaginationRequest;
use App\Http\Resources\SongResource;
use App\Mappers\PaginationMapper;
use App\Mappers\SongMapper;
use App\Models\Song;
use App\Services\SongService;
use App\Services\SongServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JsonMapper_Exception;

class SongController extends BaseController
{
    private SongService $songService;

    public function __construct(SongServiceInterface $songService)
    {
        $this->songService = $songService;
    }

    /**
     * @throws JsonMapper_Exception
     */
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
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
            'user_id' => 'required|integer',
            'year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $song = Song::create($input);

        return $this->sendResponse(new SongResource($song), 'Song created successfully.');
    }

    // Display the specified resource.
    public function show($id): JsonResponse
    {
        $song = Song::find($id);

        if (is_null($song)) {
            return $this->sendError('Song not found.');
        }

        return $this->sendResponse(new SongResource($song), 'Song retrieved successfully.');
    }

    // Update the specified resource in storage.
    public function update(Request $request, Song $song): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
            'user_id' => 'required|integer',
            'year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $song->name = $input['name'];
        // $song->singer()_id = $input['singer_id'];
        $song->year = $input['year'];
        $song->save();

        return $this->sendResponse(new SongResource($song), 'Song updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy(Song $song): JsonResponse
    {
        $song->delete();

        return $this->sendResponse([], 'Song deleted successfully.');
    }
}
