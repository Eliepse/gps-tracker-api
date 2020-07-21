<?php


namespace App\Http\Controllers\Dashboard;


use App\App;
use App\GpsTrack;
use Carbon\Carbon;

class DashboardController
{
	public function home(App $app)
	{
		$app->load(['tracks', 'tracks.points']);

		$tracksDistances = $app->tracks
			->map(function (GpsTrack $track) {
				return [
					"id" => $track->id,
					"distance" => $track->getDistance() / 1_000,
					"duration" => $track->getDuration(),
					"time" => $track->created_at,
				];
			});

		$weeklyDistances = collect();

		for (
			$weekStart = Carbon::now()->startOfWeek()->subWeeks(3);
			$weekStart->isBefore(Carbon::now());
			$weekStart->addWeek()
		) {
			$weekEnd = $weekStart->clone()->endOfWeek();
			$distance = $tracksDistances
				->filter(function (array $track) use ($weekStart, $weekEnd) {
					return $track["time"]->between($weekStart, $weekEnd);
				})
				->sum("distance");

			$weeklyDistances->put(
				$weekStart->timestamp,
				round($distance, 1)
			);
		}
//		$weeklyDistances = $tracksDistances
//			->groupBy(function (array $track) { return $track["time"]->clone()->startOfWeek()->timestamp; })
//			->map(function ($tracks) { return round($tracks->sum("distance"), 1); })
//			->take(-4);

		return view("dashboard.total", [
			"app" => $app,
			"tracksDistances" => $tracksDistances,
			"tracks" => $app->tracks,
			"weekly" => $weeklyDistances,
		]);
	}
}