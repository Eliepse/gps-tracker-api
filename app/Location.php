<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class GpsPoint
 *
 * @package App
 * @property float $longitude
 * @property float $latitude
 * @property float $accuracy
 * @property float $altitude
 * @property Carbon $time
 * @property-read Track $track
 */
class Location extends Model
{
	protected $table = "locations";

	protected $guarded = [];

	public $timestamps = false;

	protected $dates = [
		"time",
	];


	public function track(): BelongsTo
	{
		return $this->belongsTo(Track::class, "track_id", "id");
	}


	/**
	 * Calculates the distance, in meters, between the actual point and the given point
	 *
	 * @param Location $location The point to calculate the distance to
	 *
	 * @return float The distance in meters
	 */
	public function distanceTo(Location $location): float
	{
		return self::distanceBetween(
			$this->longitude, $this->latitude,
			$location->longitude, $location->latitude,
		);
	}


	/**
	 * @param float $val A longitude from -180° to 180°
	 *
	 * @noinspection PhpUnused
	 */
	public function setLongitudeAttribute(float $val)
	{
		$this->attributes["longitude"] = round($val, 8);
	}


	/**
	 * @param float $val A latitude from -90° to 90°
	 *
	 * @noinspection PhpUnused
	 */
	public function setLatitudeAttribute(float $val)
	{
		$this->attributes["latitude"] = round($val, 8);
	}


	/**
	 * @param float|null $val
	 *
	 * @noinspection PhpUnused
	 */
	public function setAltitudeAttribute(?float $val)
	{
		$this->attributes["altitude"] = round($val, 8);
	}


	/**
	 * @param float $val
	 *
	 * @noinspection PhpUnused
	 */
	public function setAccuracyAttribute(float $val)
	{
		$this->attributes["accuracy"] = round($val, 2);
	}


	public static function trackLength(int $track_id): int
	{
		return self::pathLength(
			DB::table("locations")
				->select(["longitude", "latitude"])
				->where("track_id", $track_id)
				->orderBy("time")
				->get()
		);
	}


	public static function pathLength(Collection $locations): int
	{
		$distance = 0.0;
		$count = count($locations);
		for ($h = 0, $i = 1; $i < $count; $i++, $h++) {
			$distance += self::distanceBetween(
				$locations[ $i ]->longitude, $locations[ $i ]->latitude,
				$locations[ $h ]->longitude, $locations[ $h ]->latitude,
			);
		}
		return round($distance);
	}


	public static function distanceBetween(float $a_lng, float $a_lat, float $b_lng, float $b_lat): float
	{
		// Equirectangular calculation
		$x = deg2rad(abs($b_lng) - abs($a_lng)) * cos(deg2rad($a_lat + $b_lat) / 2);
		$y = deg2rad($a_lat - $b_lat);
		return sqrt($x * $x + $y * $y) * 6378137;
	}
}
