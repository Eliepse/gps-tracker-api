<?php

namespace App\Http\Controllers\Api;

use App\App;
use App\GpsTrack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class GpsTrackResourceController
{
	public function index(Request $request, App $app = null): JsonResponse
	{
		$app = $app ?: $request->user();

		// TODO: use Laravel's Resources
		$tracks = $app->tracks()
			->select(["id", "app_id"])
			->with(["points:id,gps_track_id,longitude,latitude"])
			->get();

		return response()->json($tracks);
	}


	public function show(int $track)
	{
		return response()->json(
			GpsTrack::query()->select(["id"])
				->findOrFail($track)
				->load("points:id,gps_track_id,longitude,latitude")
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