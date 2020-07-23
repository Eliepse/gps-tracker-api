<?php

/** @var Factory $factory */

use App\User;
use App\Track;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Track::class, function (Faker $faker) {
	return [
		'user_id' => fn() => factory(User::class),
	];
});
