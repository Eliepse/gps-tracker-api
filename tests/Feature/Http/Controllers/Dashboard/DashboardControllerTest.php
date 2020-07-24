<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\MapController;
use App\Track;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function it_uses_correct_view()
	{
		$this->get(action(DashboardController::class, [factory(User::class)->create()]))
			->assertOk()
			->assertViewIs("dashboard.total");
	}


	/** @test */
	public function it_can_list_tracks()
	{
		$user = factory(User::class)->create();
		$tracks = $user->tracks()->save(factory(Track::class)->make());
		$this->get(action(DashboardController::class, [$user]))
			->assertOk()
			->assertSee(action(MapController::class, [$user, $tracks->first()]));
	}
}
