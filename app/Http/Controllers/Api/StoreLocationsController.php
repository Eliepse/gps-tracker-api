<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationsStoredEvent;
use App\Location;
use App\Track;
use App\Http\Requests\StoreLocationsRequest;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class StoreLocationsController
{
	public function __invoke(StoreLocationsRequest $request, Track $track): Response
	{
		if (! $track->user->is($request->user())) {
			return response()->noContent(403);
		}

		$locations = collect($request->get("locations"))
			->transform(function (array $item) {
				return new Location([
					'latitude' => $item['latitude'],
					'longitude' => $item['longitude'],
					'altitude' => $item['altitude'] ?? null,
					'accuracy' => $item['accuracy'],
					'time' => Carbon::createFromTimestamp($item['time']),
				]);
			});

		$track->locations()->saveMany($locations);
		$track->load("locations:id,track_id,longitude,latitude,time");
		$track->distance = $track->calculateDistance(true);
		$track->duration = $track->calculateDuration(true);
		$track->save();

		event(new LocationsStoredEvent($track, $locations->toArray()));

		return response()->json(['status' => 'ok']);
	}
}
