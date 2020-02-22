<?php

namespace App\Http\Controllers\Api;

use App\App;
use App\GpsTrack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestNewGpsTrackController
{
	/**
	 * @param Request $request
	 *
	 * @return JsonResponse|Response
	 */
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
