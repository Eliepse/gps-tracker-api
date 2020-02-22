<?php

namespace App\Http\Controllers;

use App\App;
use App\GpsTrack;
use App\Http\Requests\StoreGpsTrackRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class GpsTrackController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return View
	 */
	public function index()
	{
		/** @var Collection $tracks */
		$tracks = GpsTrack::with('app')->get();

		return view('list', [
			'tracks' => $tracks,
		]);
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
		$app = App::first();

		/** @var GpsTrack $track */
		$track = $app->tracks()
			->create($request->all(['nodes']));

		return response()->json([
			'url' => action([self::class, 'show'], [$track]),
		]);
	}
}
