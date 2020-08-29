<?php

namespace Tests\Unit;

use App\Track;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * Add a serie of GpsPoints to the given GpsTrack
	 *
	 * @param Track $track
	 * @param int $steps
	 *
	 * @return Track
	 * @throws \Exception
	 */
	private function traceTrack(Track $track, int $steps = 10): Track
	{
		$longitude = 2.3472;
		$latitude = 48.8579;
		$accuracy = (random_int(5, 16) * 1000.0) / 1000.0;
		$altitude = (random_int(100, 500) * 1000.0) / 1000.0;
		$time = Carbon::now();

		for (; $steps > 0; $steps--) {
			$track->locations()->create([
				"longitude" => $longitude,
				"latitude" => $latitude,
				"accuracy" => $accuracy,
				"altitude" => $altitude,
				"time" => $time,
			]);

			$longitude += random_int(-5, 5) / 1000.0;
			$latitude += random_int(-5, 5) / 1000.0;
			$accuracy = (random_int(5, 16) * 1000.0) / 1000.0;
			$altitude += (random_int(-10, 10) * 1000.0) / 1000.0;
			$time->addSeconds(15);
		}

		$track->load("locations");
		return $track;
	}


	/** @test */
	public function factory_works()
	{
		$track = factory(Track::class)->create();
		$this->assertEquals(Track::class, get_class($track));
		$this->assertTrue($track->exists);
	}


	/** @test */
	public function it_stores_locations_relation()
	{
		$track = $this->traceTrack(factory(Track::class)->create(), 10);
		$this->assertCount(10, $track->locations()->get());
	}


	/** @test */
	public function it_has_non_null_duration()
	{
		$track = $this->traceTrack(factory(Track::class)->create(), 10);
		// The first point is stored at deltaTime = 0, so the duration is not 150 but '(10 - 1) * 15'
		$this->assertEquals(135, $track->calculateDuration()->totalSeconds);
	}


	/** @test */
	public function it_has_non_null_distance()
	{
		$track = $this->traceTrack(factory(Track::class)->create(), 10);
		$this->assertGreaterThan(0, $track->getDistance());
	}


	/** @test */
	public function it_returns_zero_on_unsufficient_locations()
	{
		/** @var Track $track */
		$track = factory(Track::class)->create();
		$this->assertEquals(0, $track->getDistance());
		$this->assertEquals(0, $track->calculateDuration()->totalSeconds);

		$track = $this->traceTrack(factory(Track::class)->create(), 1);
		$this->assertEquals(0, $track->getDistance());
		$this->assertEquals(0, $track->calculateDuration()->totalSeconds);
	}


	/** @test */
	public function it_caches_distance()
	{
		$track = $this->traceTrack(factory(Track::class)->create(), 2);
		$first_distance = $track->getDistance();
		$track = $this->traceTrack($track, 3);

		$this->assertEquals($first_distance, $track->getDistance());
		$this->assertGreaterThan($first_distance, $track->getDistance(true));
	}


	/** @test */
	public function it_casts_duration()
	{
		/** @var Track $track */
		$track = factory(Track::class)->make();

		$track->duration = CarbonInterval::hours(150)->minutes(15)->seconds(01);
		$this->assertEquals("150:15:01", $track->getAttributes()["duration"]);

		$track->duration = CarbonInterval::hours(1)->minutes(15)->seconds(01)->totalSeconds;
		$this->assertEquals("01:15:01", $track->getAttributes()["duration"]);

		$track->duration = $interval = CarbonInterval::hours(170)->minutes(07)->seconds(17);
		$this->assertEquals("170:07:17", $track->getAttributes()["duration"]);

		$this->assertEquals($interval, $track->duration);
	}
}
