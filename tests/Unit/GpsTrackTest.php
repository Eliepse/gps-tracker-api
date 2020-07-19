<?php

namespace Tests\Unit;

use App\GpsPoint;
use App\GpsTrack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GpsTrackTest extends TestCase
{
	use RefreshDatabase;

	private function traceTrack(GpsTrack $track): GpsTrack
	{
		return $track;
	}


	/** @test */
	public function factory_works()
	{
		$track = factory(GpsTrack::class)->create();
		$this->assertEquals(GpsTrack::class, get_class($track));
		$this->assertTrue($track->exists);
	}
}
