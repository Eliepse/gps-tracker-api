<?php

namespace Tests\Unit;

use App\GpsPoint;
use App\GpsTrack;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GpsTrackTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * Add a serie of GpsPoints to the given GpsTrack
	 *
	 * @param GpsTrack $track
	 * @param int $steps
	 *
	 * @return GpsTrack
	 * @throws \Exception
	 */
	private function traceTrack(GpsTrack $track, int $steps = 10): GpsTrack
	{
		$longitude = 2.3472;
		$latitude = 48.8579;
		$accuracy = (random_int(5, 16) * 1000.0) / 1000.0;
		$altitude = (random_int(100, 500) * 1000.0) / 1000.0;
		$time = Carbon::now();

		for (; $steps > 0; $steps--) {
			$track->points()->create([
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

		$track->load("points");
		return $track;
	}


	/** @test */
	public function factory_works()
	{
		$track = factory(GpsTrack::class)->create();
		$this->assertEquals(GpsTrack::class, get_class($track));
		$this->assertTrue($track->exists);
	}


	/** @test */
	public function it_stores_gps_points_relation()
	{
		$track = $this->traceTrack(factory(GpsTrack::class)->create(), 10);
		$this->assertCount(10, $track->points()->get());
	}


	/** @test */
	public function it_has_non_null_duration()
	{
		$track = $this->traceTrack(factory(GpsTrack::class)->create(), 10);
		// The first point is stored at deltaTime = 0, so the duration is not 150 but '(10 - 1) * 15'
		$this->assertEquals(135, $track->getDuration()->totalSeconds);
	}


	/** @test */
	public function it_has_non_null_distance()
	{
		$track = $this->traceTrack(factory(GpsTrack::class)->create(), 10);
		$this->assertGreaterThan(0, $track->getDistance());
	}


	/** @test */
	public function it_returns_zero_on_unsufficient_points()
	{
		/** @var GpsTrack $track */
		$track = factory(GpsTrack::class)->create();
		$this->assertEquals(0, $track->getDistance());
		$this->assertEquals(0, $track->getDuration()->totalSeconds);

		$track = $this->traceTrack(factory(GpsTrack::class)->create(), 1);
		$this->assertEquals(0, $track->getDistance());
		$this->assertEquals(0, $track->getDuration()->totalSeconds);
	}


	/** @test */
	public function it_caches_distance()
	{
		$track = $this->traceTrack(factory(GpsTrack::class)->create(), 2);
		$first_distance = $track->getDistance();
		$track = $this->traceTrack($track, 3);

		$this->assertEquals($first_distance, $track->getDistance());
		$this->assertGreaterThan($first_distance, $track->getDistance(true));
	}
}
