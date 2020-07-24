<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\MapController;
use App\Track;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function it_return_not_found_on_unexistant_record()
	{
		$this->getJson(action(MapController::class, [factory(User::class)->create(), 156]))
			->assertNotFound();
	}


	/** @test */
	public function it_uses_correct_view()
	{
		/** @var User $user */
		$user = factory(User::class)->create();
		$this->get(action(MapController::class, [$user]))
			->assertViewIs("dashboard.map");
		$this->get(action(MapController::class, [$user, $user->tracks()->create()]))
			->assertViewIs("dashboard.map");
	}
}
