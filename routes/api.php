<?php

use App\Http\Controllers\Api\GpsTrackResourceController;
use App\Http\Controllers\Api\StoreGpsPointsController;
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

Route::middleware('auth:api')->post('track/{track}/points', StoreGpsPointsController::class);
Route::middleware('auth:api')->post("track", GpsTrackResourceController::class . "@store");
Route::middleware('auth:api')->get("tracks", GpsTrackResourceController::class . "@index");

Route::get("apps/{app}/tracks", GpsTrackResourceController::class . "@index");
Route::get("tracks/{track}", GpsTrackResourceController::class . "@show");