<?php

namespace App\Http\Controllers\Api;

use App\GpsTrack;
use App\Http\Requests\StoreGpsTrackRequest;

class StoreGpsPointsController
{
	public function __invoke(StoreGpsTrackRequest $request, GpsTrack $track)
	{
		$track->points()->createMany($request->get('points'));

		return response(null, 200)->json();
	}
}
