<?php

namespace App\Http\Controllers\Api;

use App\App;
use App\GpsTrack;
use Illuminate\Http\Request;

class RequestNewGpsTrackController
{
	public function __invoke(Request $request)
	{
		/** @var App|null $app */
		$app = $request->user();

		if (!is_a($app, App::class)) {
			return response(null, 403);
		}

		/** @var GpsTrack $track */
		$track = $app->tracks()->save(new GpsTrack(['nodes' => []]));

		return response(null, 200)->json([
			'track_id' => $track->id,
		]);
	}
}
