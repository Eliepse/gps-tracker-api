<?php

namespace App\Http\Controllers\Api;

use App\Location;
use App\User;
use App\Track;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

final class TrackResourceController
{
	public function index(Request $request, User $user = null): JsonResponse
	{
		$user = $user ?: $request->user();
		$tracks = [];
		$locations = DB::table('locations')
			->select(['track_id', 'longitude', 'latitude'])
			->orderBy('time')
			->whereIn('track_id', function (Builder $query) use ($user) {
				$query->select('id')->from('tracks')
					->where('user_id', $user->id)
					->whereDate('created_at', '>=', Carbon::today()->subMonth());
			})
			->get();

		foreach ($locations as $location) {
			if (! isset($tracks[ $location->track_id ])) {
				$tracks[ $location->track_id ] = ['id' => $location->track_id, 'locations' => []];
			}
			$tracks[ $location->track_id ]['locations'][] = [
				'longitude' => $location->longitude,
				'latitude' => $location->latitude,
			];
		}

		return response()->json(array_values($tracks));
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