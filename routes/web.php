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
use App\Http\Controllers\Dashboard\RedirectToLastTrackController;
use Illuminate\Support\Facades\Route;

Route::get('/app/{app}', [DashboardController::class, 'home']);
Route::get('/users/{user}/map/last', RedirectToLastTrackController::class)->name('map:last');
Route::get('/users/{user}/map/{track?}', MapController::class)->name('map');
