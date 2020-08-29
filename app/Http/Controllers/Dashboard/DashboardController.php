<?php


namespace App\Http\Controllers\Dashboard;


use App\User;
use Carbon\Carbon;

class DashboardController
{
	public function __invoke(User $user)
	{
		$past_tracks = $user->tracks()
			->whereDate("created_at", ">=", Carbon::now()->subWeeks(3)->startOfWeek())
			->orderBy("created_at")
			->get(["id", "user_id", "distance", "duration", "created_at"]);

		$weekly_km = $past_tracks
			->groupBy(fn($track) => $track->created_at->clone()->startOf('week', Carbon::MONDAY)->timestamp)
			->map(fn($week_tracks) => $week_tracks->sum("distance"));

		return view("dashboard.total", [
			"user" => $user,
			"past_tracks" => $past_tracks,
			"total_distance" => $user->tracks()->sum("distance"),
			"tracks_count" => $user->tracks()->count(),
			"weekly" => $weekly_km,
		]);
	}
}