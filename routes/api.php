<?php

use App\Http\Controllers\Api\TrackResourceController;
use App\Http\Controllers\Api\StoreLocationsController;
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

Route::middleware('auth:api')->post('track/{track}/points', StoreLocationsController::class);
Route::middleware('auth:api')->post("track", TrackResourceController::class . "@store");
Route::middleware('auth:api')->get("tracks", TrackResourceController::class . "@index");

Route::get("apps/{user}/tracks", TrackResourceController::class . "@index");
Route::get("tracks/{track}", TrackResourceController::class . "@show");