<?php


namespace App\Http\Controllers\Dashboard;


use App\App;

class MapController
{
	public function __invoke(App $app)
	{
		$app->load("tracks.points");

		return view("dashboard.map", [
			"app" => $app,
			"data" => $app->tracks
		]);
	}
}