<?php

namespace App\Http\Controllers\Dashboard;

use App\Track;
use App\User;
use Illuminate\Http\RedirectResponse;

final class RedirectToLastTrackController
{
	public function __invoke(User $user): RedirectResponse
	{
		/** @var Track $track */

		return redirect()->route("map", [
			$user,
			$track = $user->tracks()
				->orderBy("id", "DESC")
				->firstOrFail(["id", "user_id"]),
		]);
	}
}