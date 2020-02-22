<?php

namespace App\Http\Controllers\Api;

use App\App;
use App\GpsPoint;
use App\GpsTrack;
use App\Http\Requests\StoreGpsTrackRequest;
use Illuminate\Http\Request;

class StoreGpsPointsController
{
	public function __invoke(StoreGpsTrackRequest $request, GpsTrack $track)
	{
		$track->points()->createMany($request->get('points'));

		return response(null, 200)->json();
	}
}
