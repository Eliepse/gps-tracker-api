<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Distances;
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
		$hasIds = $request->has('ids');
		$tracks = [];
		$bounds = [[90, 180], [0, 0]]; // [min corner, max corner]
		$query = DB::table('locations');

		if ($hasIds) {
			$query->selectRaw("UNIX_TIMESTAMP(time) AS time");
		}

		$query->addSelect(['track_id', 'longitude', 'latitude'])
			->orderBy('time')
			->whereIn('track_id', function (Builder $query) use ($user, $hasIds) {
				$query->select('id')->from('tracks')
					->where('user_id', $user->id);
				if (! $hasIds) {
					$query->whereDate('created_at', '>=', Carbon::today()->subMonth());
				}
			});

		// Filter by ids
		// And prevent sql inject by taking only integers
		if ($ids = $request->get("ids")) {
			$query->whereIn("track_id", array_map(fn($val) => intval($val), $ids));
		}

		foreach ($query->get() as $key => $location) {
			if (! isset($tracks[ $location->track_id ])) {
				$tracks[ $location->track_id ] = ['id' => $location->track_id, 'locations' => []];
			}
			// Check min bound
			if ($bounds[0][0] > $location->latitude) {
				$bounds[0][0] = $location->latitude;
			}
			if ($bounds[0][1] > $location->longitude) {
				$bounds[0][1] = $location->longitude;
			}
			// Check max bound
			if ($bounds[1][0] < $location->latitude) {
				$bounds[1][0] = $location->latitude;
			}
			if ($bounds[1][1] < $location->longitude) {
				$bounds[1][1] = $location->longitude;
			}
			// Check min longitude bound
			// Check max bound
			$loc = [$location->latitude, $location->longitude];

			if ($hasIds) {
				$loc[2] = $location->time;
			}

			array_push($tracks[ $location->track_id ]['locations'], $loc);
		}

		return response()->json([
			"bounds" => $bounds,
			"rows" => count($tracks),
			"data" => array_values($tracks),
		]);
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