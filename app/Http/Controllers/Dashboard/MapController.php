<?php


namespace App\Http\Controllers\Dashboard;


use App\User;
use App\Track;

class MapController
{
	public function __invoke(User $user, Track $track = null)
	{
		return view("dashboard.map", [
			"user" => $user,
			"track" => $track
		]);
	}
}