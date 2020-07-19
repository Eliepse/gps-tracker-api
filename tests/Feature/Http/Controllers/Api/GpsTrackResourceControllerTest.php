<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\App;
use App\Http\Controllers\Api\GpsTrackResourceController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GpsTrackResourceControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function it_creates_new_track_and_returns_id()
	{
		$this
			->withHeader("Authorization", "Bearer " . factory(App::class)->create()->api_token)
			->postJson(action([GpsTrackResourceController::class, "store"]))
			->assertOk()
			->assertJson(["track_id" => 1]);
	}
}
