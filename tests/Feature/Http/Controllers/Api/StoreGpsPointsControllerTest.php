<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\App;
use App\GpsPoint;
use App\GpsTrack;
use App\Http\Controllers\Api\StoreGpsPointsController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreGpsPointsControllerTest extends TestCase
{
	use RefreshDatabase;

	private function makePoints(): array
	{
		return factory(GpsPoint::class, 10)
			->make()
			->map(function (GpsPoint $point) {
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
		$track = factory(GpsTrack::class)->create();
		$this->withHeader("Authorization", "Bearer " . $track->app->api_token)
			->postJson(action(StoreGpsPointsController::class, [$track]), ["points" => $this->makePoints()])
			->assertSuccessful()
			->assertJson(["status" => "ok"]);
		$this->assertCount(10, $track->points);
	}
}
