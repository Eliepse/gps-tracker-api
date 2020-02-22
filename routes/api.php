<?php

use App\Http\Controllers\Api\RequestNewGpsTrackController;
use App\Http\Controllers\Api\StoreGpsPointsController;
use App\Http\Controllers\GpsTrackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')
//	->get('/user', function (Request $request) {
//		return $request->user();
//	});

Route::middleware('auth:api')
	->resource('tracks', GpsTrackController::class, [
		'only' => ['store', 'show', 'index'],
	]);

Route::middleware('auth:api')->post('track/{track}/points', StoreGpsPointsController::class);
Route::middleware('auth:api')->post('track', RequestNewGpsTrackController::class);
