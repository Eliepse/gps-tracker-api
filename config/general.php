<?php

return [

	/*
	| General
	|
	| This file is for storing configurations that change the behavior of the
	| app that does not change Laravel's process.
	*/

	/*
	|--------------------------------------------------------------------------
	| Calculations
	|--------------------------------------------------------------------------
	| Settings to adjust calculation that can be tweeked, such as values that
	| are estimated. Those calculation often involves uncertainty because of
	| the inaccurate caracteristic of the data.
	*/

	'calculations' => [
		'idle_speed_thershold' => 1.5 / 3.6, // in m/s (km/h = m/s * 3.6)
	],

];
