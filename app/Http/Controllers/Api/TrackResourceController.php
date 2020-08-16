<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Track;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class TrackResourceController
{
	public function index(Request $request, User $user = null): JsonResponse
	{
		$user = $user ?: $request->user();

		// TODO: use Laravel's Resources
		$tracks = $user->tracks()
			->select(["id", "user_id"])
			->whereDate("created_at", ">=", Carbon::today()->subMonth())
			->with(["locations:id,track_id,longitude,latitude"])
			->get();

		return response()->json($tracks);
	}


	public function show(int $track)
	{
		return response()->json(
			Track::query()->select(["id"])
				->findOrFail($track)
				->load("locations:id,track_id,longitude,latitude")
		);
	}


	public function store(Request $request): Response
	{
		return response()
			->json([
				'track_id' => $request->user()->tracks()->create()->id,
			]);
	}
}