<?php


namespace App\Http\Controllers;


use App\Action\GpsTrackToSvgAction;
use App\GpsTrack;
use Carbon\CarbonInterval;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class GpsTrackSvgController
{
	private $ttl = 0;
	/**
	 * Display the specified resource as SVG.
	 *
	 * @param GpsTrack $track
	 *
	 * @return Response
	 */
	public function __invoke(GpsTrack $track)
	{
		$svg = Cache::remember("track:svg:{$track->id}", $this->ttl, function () use ($track) {
			return (new GpsTrackToSvgAction(3))($track);
		});

		return response($svg, 200, [
			'Content-Type' => 'image/svg+xml',
		]);
	}
}