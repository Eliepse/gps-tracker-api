<?php

use App\Http\Controllers\Api\StorePointsController;
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

/** @deprecated */
Route::middleware('auth:api')->post('track/{track}/points', StorePointsController::class);
/** @deprecated */
Route::middleware('auth:api')->post("track", TrackResourceController::class . "@store");
/** @deprecated */
Route::get("apps/{user}/tracks", TrackResourceController::class . "@index");

Route::middleware('auth:api')->post('tracks/{track}/locations', StoreLocationsController::class);
Route::middleware('auth:api')->post("tracks", TrackResourceController::class . "@store");
Route::middleware('auth:api')->get("tracks", TrackResourceController::class . "@index");

Route::get("users/{user}/tracks", TrackResourceController::class . "@index");
/** @deprecated Use "users/{user}/tracks" with the id in 'ids' array parameter */
Route::get("tracks/{track}", TrackResourceController::class . "@show");