<?php

namespace App\Http\Controllers\Dashboard;

use App\GpsTrack;
use Illuminate\Http\RedirectResponse;

final class RedirectToLastTrackController
{
	public function __invoke(): RedirectResponse
	{
		/** @var GpsTrack $track */
		$track = GpsTrack::query()
			->orderBy("id", "DESC")
			->firstOrFail(["id", "app_id"]);

		return redirect()->route("map", [$track->app_id, $track->id]);
	}
}