<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationsStoredEvent;
use App\Location;
use App\Track;
use App\Http\Requests\StoreLocationsRequest;
use Carbon\Carbon;

class StoreLocationsController
{
	public function __invoke(StoreLocationsRequest $request, Track $track)
	{
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

		event(new LocationsStoredEvent($track, $locations->toArray()));

		return response()->json(['status' => 'ok']);
	}
}
