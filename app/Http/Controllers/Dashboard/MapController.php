<?php


namespace App\Http\Controllers\Dashboard;


use App\App;
use App\GpsTrack;
use Illuminate\Support\Collection;

class MapController
{
	public function __invoke(App $userApp, GpsTrack $track = null)
	{
		if ($track) {
			$userApp->tracks()->findOrFail($track->id);
			$track->load("points:id,gps_track_id,longitude,latitude");
			$tracks = Collection::wrap($track);
		} else {
			$userApp->load("tracks.points:id,gps_track_id,longitude,latitude");
			$tracks = $userApp->tracks;
		}

		return view("dashboard.map", [
			"app" => $userApp,
			"data" => $tracks,
		]);
	}
}