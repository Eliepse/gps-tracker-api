<?php

namespace Tests\Unit;

use App\GpsPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GpsPointTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function it_can_be_generated_from_factory()
	{
		$point = factory(GpsPoint::class)->create();
		$this->assertDatabaseHas("gps_points", ["id" => $point->id]);
	}
}
