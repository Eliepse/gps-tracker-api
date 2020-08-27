<?php


namespace App\Http\Controllers\Dashboard;


use App\Cache\UserDistances;
use App\User;
use App\Track;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardController
{
	public function __invoke(User $user)
	{
		$tracks_km = collect();
		$from_date = Carbon::now()->subWeeks(3)->startOfWeek();
		/** @var UserDistances $cached_distances */
		$cached_distances = Cache::get("users:distances", new UserDistances());
		$total_distance = 0;

		$query = $user->tracks()->select(["id", "user_id", "created_at"]);

		if ($cached_distances->isValid() && $cached_distances->isUserCached($user->id)) {
			$query->whereDate("created_at", ">=", $cached_distances->cached_until);
			$total_distance = $cached_distances->getUserDistance($user->id);
		}

		$query->chunk(50, function (Collection $tracks) use ($tracks_km) {
			$tracks->load(["locations:id,track_id,longitude,latitude,time"]);
			/** @var Track $track */
			foreach ($tracks as $track) {
				$tracks_km->put($track->id, [
					"distance" => $track->getDistance() / 1_000,
					"duration" => $track->getDuration(),
					"time" => $track->created_at,
				]);
			}
		});

		$total_distance += $tracks_km->sum("distance");
		$weekly_km = $tracks_km->filter(fn(array $track) => $from_date->isBefore($track["time"]))
			->groupBy(fn(array $track) => $track['time']->startOf('week', Carbon::MONDAY)->timestamp)
			->map(fn($tracks) => round($tracks->sum("distance")));

		return view("dashboard.total", [
			"user" => $user,
			"tracksDistances" => $tracks_km,
			"total_distance" => $total_distance,
			"tracks_count" => $user->tracks()->count(),
			"weekly" => $weekly_km,
		]);
	}
}