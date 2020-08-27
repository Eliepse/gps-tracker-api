<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
	 * @param Location $point The point to calculate the distance to
	 *
	 * @return float The distance in meters
	 */
	public function distanceTo(Location $point): float
	{
		// Equirectangular calculation
		$lng1 = abs($this->longitude);
		$lng2 = abs($point->longitude);
		$x = deg2rad($lng2 - $lng1) * cos(deg2rad($this->latitude + $point->latitude) / 2);
		$y = deg2rad($this->latitude - $point->latitude);
		return sqrt($x * $x + $y * $y) * 6378137;
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
}
