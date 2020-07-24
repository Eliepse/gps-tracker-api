<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Track;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectToLastTrackControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function redirect_to_last_track(): void
	{
		/** @var User $user */
		$user = factory(User::class)->create();
		$user->tracks()->saveMany($tracks = factory(Track::class, 3)->make());

		factory(Track::class)->create(["user_id" => 2]);

		$this->get(route("map:last", [1]))
			->assertRedirect(route("map", [1, $tracks->last()->id]));
	}


	/** @test */
	public function fails_when_no_track_recorded(): void
	{
		$user = factory(User::class)->create();
		$this->get(route("map:last", [$user->id]))
			->assertNotFound();
	}
}
