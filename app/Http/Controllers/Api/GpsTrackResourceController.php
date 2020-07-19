<?php

namespace App\Http\Controllers\Api;

use App\App;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class GpsTrackResourceController
{
	public function store(Request $request): Response
	{
		return response()
			->json([
				'track_id' => $request->user()->tracks()->create()->id,
			]);
	}
}