<?php

namespace App\Http\Controllers\Api;

use App\GpsPoint;
use App\GpsTrack;
use App\Http\Requests\StoreGpsPointsRequest;
use Carbon\Carbon;

class StoreGpsPointsController
{
	public function __invoke(StoreGpsPointsRequest $request, GpsTrack $track)
	{
		$points = collect($request->get("points"))
			->transform(function (array $item) {
				return new GpsPoint([
					'latitude' => $item['latitude'],
					'longitude' => $item['longitude'],
					'accuracy' => $item['accuracy'],
					'time' => Carbon::createFromTimestamp(round($item['time'] / 1000)),
				]);
			});

		$track->points()->saveMany($points);

		return response()->json(['status' => 'ok']);
	}
}
