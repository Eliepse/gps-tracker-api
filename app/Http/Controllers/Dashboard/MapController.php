<?php


namespace App\Http\Controllers\Dashboard;


use App\App;
use App\GpsTrack;

class MapController
{
	public function __invoke(App $userApp, GpsTrack $track = null)
	{
		$userApp->load("tracks.points");

		return view("dashboard.map", [
			"app" => $userApp,
			"data" => $userApp->tracks
		]);
	}
}