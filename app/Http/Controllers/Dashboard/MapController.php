<?php


namespace App\Http\Controllers\Dashboard;


use App\User;
use App\Track;
use Illuminate\Support\Collection;

class MapController
{
	public function __invoke(User $userApp, Track $track = null)
	{
//		if ($track) {
//			$userApp->tracks()->findOrFail($track->id);
//			$track->load("points:id,gps_track_id,longitude,latitude");
//			$tracks = Collection::wrap($track);
//		} else {
//			$userApp->load("tracks.points:id,gps_track_id,longitude,latitude");
//			$tracks = $userApp->tracks;
//		}

		return view("dashboard.map", [
			"app" => $userApp,
			"track" => $track
//			"data" => $tracks,
		]);
	}
}