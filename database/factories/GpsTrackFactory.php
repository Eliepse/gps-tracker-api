<?php

/** @var Factory $factory */

use App\App;
use App\GpsTrack;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(GpsTrack::class, function (Faker $faker) {
	return [
		'app_id' => fn() => factory(App::class),
	];
});
