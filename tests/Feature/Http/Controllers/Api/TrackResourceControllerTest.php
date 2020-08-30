<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\User;
use App\Location;
use App\Track;
use App\Http\Controllers\Api\TrackResourceController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackResourceControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function it_creates_new_track_and_returns_id()
	{
		$this->withHeader("Authorization", "Bearer " . factory(User::class)->create()->api_token)
			->postJson(action([TrackResourceController::class, "store"]))
			->assertOk()
			->assertJson(["track_id" => 1]);
	}


	/** @test */
	public function it_returns_json_of_tracks_with_auth()
	{
		$user = factory(User::class)->create();
		factory(Track::class, 2)
			->create(["user_id" => $user->id])
			->each(function (Track $track) {
				factory(Location::class, 5)->create(["track_id" => $track->id]);
			});

		$this->withHeader("Authorization", "Bearer " . $user->api_token)
			->getJson("api/tracks")
			->assertOk()
			->assertJsonStructure([["id", "locations" => [["longitude", "latitude"]]]]);
	}


	/** @test */
	public function it_returns_json_of_tracks_without_auth()
	{
		$user = factory(User::class)->create();
		factory(Track::class, 2)
			->create(["user_id" => $user->id])
			->each(function (Track $track) {
				factory(Location::class, 5)->create(["track_id" => $track->id]);
			});

		$this->getJson(action([TrackResourceController::class, "index"], [$user]))
			->assertOk()
			->assertJsonStructure([["id", "locations" => [["longitude", "latitude"]]]]);
	}


	/** @test */
	public function it_returns_json_of_single_track()
	{
		/** @var Track $track */
		$track = factory(Track::class)->create();
		factory(Location::class, 2)->create(["track_id" => $track]);
		$this->getJson(action([TrackResourceController::class, "show"], [$track]))
			->assertOk()
			->assertJsonStructure(["id", "locations" => [["longitude", "latitude"]]]);
	}
}
