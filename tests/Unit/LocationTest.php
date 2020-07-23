<?php

namespace Tests\Unit;

use App\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function it_can_be_generated_from_factory()
	{
		$point = factory(Location::class)->create();
		$this->assertDatabaseHas("locations", ["id" => $point->id]);
	}
}
