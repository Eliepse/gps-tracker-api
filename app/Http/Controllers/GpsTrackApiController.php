<?php

namespace App\Http\Controllers;

use App\App;
use App\GpsTrack;
use App\Http\Requests\StoreGpsTrackRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GpsTrackApiController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		/** @var Collection $tracks */
		$tracks = GpsTrack::all();

		$urls = $tracks->reduce(function ($track) {
			return action([self::class, 'show'], [$track]);
		}, []);

		return response()->json($urls);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreGpsTrackRequest $request
	 *
	 * @return JsonResponse
	 */
	public function store(StoreGpsTrackRequest $request)
	{
		/** @var App $app */
		$app = $request->user();

		/** @var GpsTrack $track */
		$track = $app->tracks()
			->create($request->all(['nodes']));

		return response()->json([
			'url' => action([self::class, 'show'], [$track]),
		]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param GpsTrack $track
	 *
	 * @return JsonResponse|Response
	 */
	public function show(GpsTrack $track)
	{
		return response()->json($track->toArray());
	}
}
