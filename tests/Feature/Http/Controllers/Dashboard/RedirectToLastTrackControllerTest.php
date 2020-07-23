<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Track;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectToLastTrackControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function redirect_to_last_track(): void
	{
		$tracks = factory(Track::class, 3)->create(["user_id" => 1]);

		$this->get(route("map:last", [1]))
			->assertRedirect(route("map", [1, $tracks->last()->id]));
	}


	/** @test */
	public function fails_when_no_track_recorded(): void
	{
		$this->get(route("map:last", [1]))
			->assertNotFound();
	}
}
