<?php


namespace App\Http\Controllers\Dashboard;


use App\App;
use App\GpsTrack;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class DashboardController
{
	public function home(App $app)
	{
		$app->load(['tracks', 'tracks.points']);

		$tracksDistances = $app->tracks
			->map(function (GpsTrack $track) {
				$distance = 0.0;
				$duration = CarbonInterval::create(0);

				for ($h = 0, $i = 1; $i < $track->points->count(); $i++, $h++) {
					$distance += $track->points[ $i ]->distanceTo($track->points[ $h ]);
				}

				if ($distance > 0) {
					/** @var Carbon $last */
					$last = $track->points->last()->time;
					$duration = $last->diffAsCarbonInterval($track->points->first()->time);
				}

				return [
					"distance" => $distance,
					"duration" => $duration,
					"time" => $track->created_at,
				];
			});

		$weeklyDistances = $tracksDistances
			->groupBy(function (array $track) { return $track["time"]->clone()->startOfWeek()->timestamp; })
			->map(function ($tracks) { return round($tracks->sum("distance"), 1); })
			->take(-4);

		return view("dashboard.total", [
			"app" => $app,
			"tracksDistances" => $tracksDistances,
			"tracks" => $app->tracks,
			"weekly" => $weeklyDistances,
		]);
	}
}