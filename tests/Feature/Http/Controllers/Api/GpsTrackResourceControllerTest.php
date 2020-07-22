<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\App;
use App\GpsPoint;
use App\GpsTrack;
use App\Http\Controllers\Api\GpsTrackResourceController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GpsTrackResourceControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function it_creates_new_track_and_returns_id()
	{
		$this->withHeader("Authorization", "Bearer " . factory(App::class)->create()->api_token)
			->postJson(action([GpsTrackResourceController::class, "store"]))
			->assertOk()
			->assertJson(["track_id" => 1]);
	}


	/** @test */
	public function it_return_json_of_tracks()
	{
		$app = factory(App::class)->create();
		factory(GpsTrack::class, 2)
			->create(["app_id" => $app->id])
			->each(function (GpsTrack $track) {
				factory(GpsPoint::class, 5)->create(["gps_track_id" => $track->id]);
			});

		$this->withHeader("Authorization", "Bearer " . $app->api_token)
			->getJson(action([GpsTrackResourceController::class, "index"]))
			->assertOk()
			->assertJsonStructure([["id", "app_id", "points" => [["id", "gps_track_id", "longitude", "latitude"]],]]);
	}
}
