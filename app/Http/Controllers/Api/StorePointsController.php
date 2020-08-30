<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationsStoredEvent;
use App\Http\Requests\StorePointsRequest;
use App\Location;
use App\Track;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StorePointsController
 *
 * @package App\Http\Controllers\Api
 * @deprecated
 */
class StorePointsController
{
	public function __invoke(StorePointsRequest $request, Track $track): Response
	{
		if (! $track->user->is($request->user())) {
			return response()->noContent(403);
		}

		$locations = collect($request->get("points"))
			->transform(function (array $item) {
				return new Location([
					'latitude' => $item['latitude'],
					'longitude' => $item['longitude'],
					'altitude' => $item['altitude'] ?? null,
					'accuracy' => $item['accuracy'],
					'time' => Carbon::createFromTimestamp(round($item['time'] / 1000)),
				]);
			});

		$track->locations()->saveMany($locations);
		$track->load("locations");
		$track->distance = $track->calculateDistance(true);
		$track->duration = $track->calculateDuration(true);
		$track->save();

		event(new LocationsStoredEvent($track, $locations->toArray()));

		return response()->json(['status' => 'ok']);
	}
}
