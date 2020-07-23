<?php

/** @var Factory $factory */

use App\Location;
use App\Track;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Location::class, function (Faker $faker) {
	return [
		'longitude' => $faker->longitude,
		'latitude' => $faker->latitude,
		'accuracy' => $faker->randomFloat(2, 5, 15),
		'altitude' => $faker->randomFloat(8, 0, 500),
		'time' => Carbon::now()->addSeconds(random_int(15, 120)),
		'track_id' => fn() => factory(Track::class)->create()->id,
	];
});
