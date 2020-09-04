<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

final class Distances
{
	public static function distanceBetweenGPS(float $a_lng, float $a_lat, float $b_lng, float $b_lat): float
	{
		// Equirectangular calculation
		$x = deg2rad(abs($b_lng) - abs($a_lng)) * cos(deg2rad($a_lat + $b_lat) / 2);
		$y = deg2rad($a_lat - $b_lat);
		return sqrt($x * $x + $y * $y) * 6378137;
	}

	public static function msToKm(float $speed): float
	{
		return $speed * 3.6;
	}

	public static function pathLength(Collection $locations): int
	{
		$distance = 0.0;
		$count = count($locations);
		for ($h = 0, $i = 1; $i < $count; $i++, $h++) {
			$distance += Distances::distanceBetweenGPS(
				$locations[ $i ]->longitude, $locations[ $i ]->latitude,
				$locations[ $h ]->longitude, $locations[ $h ]->latitude,
			);
		}
		return round($distance);
	}
}