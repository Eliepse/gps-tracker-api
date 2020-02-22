<?php


namespace App\Http\Controllers\dashboard;


use App\App;
use App\GpsTrack;

class DashboardController
{
	public function home(App $app)
	{
		$app->load(['tracks', 'tracks.points']);

		$tracksDistances = $app->tracks->map(function (GpsTrack $track) {
			$distance = 0.0;
			for ($h = 0, $i = 1; $i < $track->points->count(); $i++, $h++) {
				$distance += $track->points[ $i ]->distanceTo($track->points[ $h ]);
			}

			return [
				"distance" => $distance,
				"time" => $track->created_at,
			];
		});

		$totalDistance = $tracksDistances->sum('distance');

		return view("dashboard.total", [
			"app" => $app,
			"tracksDistances" => $tracksDistances,
			"totalTracks" => $app->tracks->count(),
			"totalDistance" => $totalDistance,
		]);
	}
}