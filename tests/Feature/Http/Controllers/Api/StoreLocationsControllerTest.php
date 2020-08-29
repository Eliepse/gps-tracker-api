<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Events\LocationsStoredEvent;
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
	public function it_stores_many_locations()
	{
		$track = factory(Track::class)->create();
		$this->withHeader("Authorization", "Bearer " . $track->user->api_token)
			->postJson(action(StoreLocationsController::class, [$track]), ["locations" => $this->makeLocations()])
			->assertSuccessful()
			->assertJson(["status" => "ok"]);
		$this->assertCount(10, $track->locations);
	}


	/** @test */
	public function it_fires_an_events()
	{
		$track = factory(Track::class)->create();

		$this->expectsEvents(LocationsStoredEvent::class);

		$this->withHeader("Authorization", "Bearer " . $track->user->api_token)
			->postJson(action(StoreLocationsController::class, [$track]), ["locations" => $this->makeLocations()]);
	}


	/** @test */
	public function it_updates_tracks_metas()
	{
		/** @var Track $track */
		$track = factory(Track::class)->create();
		$this->withHeader("Authorization", "Bearer " . $track->user->api_token)
			->postJson(action(StoreLocationsController::class, [$track]), ["locations" => $this->makeLocations()]);
		$track->refresh();

		$this->assertGreaterThan(0, $pastDuration = $track->duration->totalSeconds);
		$this->assertGreaterThan(0, $pastDistance = $track->distance);

		$this->withHeader("Authorization", "Bearer " . $track->user->api_token)
			->postJson(action(StoreLocationsController::class, [$track]), ["locations" => $this->makeLocations()]);
		$track->refresh();

		$this->assertGreaterThan($pastDuration, $track->duration->totalSeconds);
		$this->assertGreaterThan($pastDistance, $track->distance);
	}
}
