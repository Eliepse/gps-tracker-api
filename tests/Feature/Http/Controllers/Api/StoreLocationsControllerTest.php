<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\User;
use App\Location;
use App\Track;
use App\Http\Controllers\Api\StoreLocationsController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreLocationsControllerTest extends TestCase
{
	use RefreshDatabase;

	private function makeLocations(): array
	{
		return factory(Location::class, 10)
			->make()
			->map(function (Location $point) {
				return [
					"longitude" => $point->longitude,
					"latitude" => $point->latitude,
					"accuracy" => $point->accuracy,
					"altitude" => $point->altitude,
					"time" => $point->time->timestamp * 1_000,
				];
			})
			->toArray();
	}


	/** @test */
	public function it_stores_many_points()
	{
		$track = factory(Track::class)->create();
		$this->withHeader("Authorization", "Bearer " . $track->user->api_token)
			->postJson(action(StoreLocationsController::class, [$track]), ["points" => $this->makeLocations()])
			->assertSuccessful()
			->assertJson(["status" => "ok"]);
		$this->assertCount(10, $track->locations);
	}
}
