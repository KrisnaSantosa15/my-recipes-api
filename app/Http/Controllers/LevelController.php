<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Http\Resources\LevelResource;

class LevelController extends Controller
{
	public function index()
	{
		$levels = Level::paginate(15);
		return LevelResource::collection($levels);
	}

	public function store(StoreLevelRequest $request)
	{
		$validated = $request->safe()->only('name');

		Level::create($validated);

		return response()->json([
			'message' => 'Level succescfully saved'
		], 200);
	}

	public function show(Level $level)
	{
		return new LevelResource($level);
	}

	public function update(UpdateLevelRequest $request, Level $level)
	{
		$validated = $request->safe()->only('name');

		$level->update($validated);

		return response()->json([
			'message' => 'Level succescfully updated'
		], 200);
	}

	public function destroy(Level $level)
	{
		$level->delete();

		return response()->json([
			'message' => 'Level succesfully deleted'
		], 200);
	}
}
