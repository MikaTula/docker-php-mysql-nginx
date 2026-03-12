<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\SongResource;
use App\Models\Song;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SongController extends BaseController
{
    // Display a listing of the resource.
    public function index(): JsonResponse
    {
        $songs = Song::all();

        return $this->sendResponse(SongResource::collection($songs), 'Songs retrieved successfully.');
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
