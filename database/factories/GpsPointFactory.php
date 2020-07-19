<?php

/** @var Factory $factory */

use App\GpsPoint;
use App\GpsTrack;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(GpsPoint::class, function (Faker $faker) {
	return [
		'longitude' => $faker->longitude,
		'latitude' => $faker->latitude,
		'accuracy' => $faker->randomFloat(2, 5, 15),
		'altitude' => $faker->randomFloat(8, 0, 500),
		'time' => Carbon::now()->addSeconds(random_int(15, 120)),
		'gps_track_id' => fn() => factory(GpsTrack::class)->create()->id,
	];
});
