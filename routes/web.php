<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\MapController;
use App\Http\Controllers\GpsTrackController;
use App\Http\Controllers\GpsTrackSvgController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'map');
Route::resource('tracks', GpsTrackController::class, [
	'only' => ['index', 'show', 'store'],
]);
Route::get('/tracks/{track}/svg', GpsTrackSvgController::class);

Route::get('/app/{app}', [DashboardController::class, 'home']);
Route::get('/app/{app}/map', MapController::class);
