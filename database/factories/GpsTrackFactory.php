<?php

/** @var Factory $factory */

use App\App;
use App\GpsTrack;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(GpsTrack::class, function (Faker $faker) {

	// TODO: Create a GpsNode object
	$trackNodes = [
		[
			"coords" => [48.86, 2.34],
			"accuracy" => 15,
			"speed" => 0,
			"time" => $faker->dateTime->getTimestamp(),
		],
	];

	for ($i = rand(2, 10), $j = 0; $i > 0; $i--, $j++) {
		$previousNode = $trackNodes[ $j ];
		$coords = $previousNode['coords'];
		$trackNodes[] = [
			"coords" => [
				$coords[0] + (random_int(-10, 10) / 1000.0),
				$coords[1] + (random_int(-10, 10) / 1000.0),
			],
			"accuracy" => 15, // TODO: randomize accuracy
			"speed" => null, // TODO: calculate speed from timestamps
			"time" => $previousNode['time'] + random_int(100, 10000),
		];
	}

	return [
		'app_id' => function () {
			return \factory(App::class);
		},
		'nodes' => $trackNodes,
	];
});
