<?php

namespace App\Http\Controllers\Dashboard;

use App\Track;
use Illuminate\Http\RedirectResponse;

final class RedirectToLastTrackController
{
	public function __invoke(): RedirectResponse
	{
		/** @var Track $track */
		$track = Track::query()
			->orderBy("id", "DESC")
			->firstOrFail(["id", "user_id"]);

		return redirect()->route("map", [$track->user_id, $track->id]);
	}
}