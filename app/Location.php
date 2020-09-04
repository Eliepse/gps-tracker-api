<?php

namespace App;

use App\Helpers\Distances;
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
		return Distances::distanceBetweenGPS(
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


	/**
	 * @param int $track_id
	 *
	 * @return int
	 * @deprecated Use Distances::pathLength directly instead
	 */
	public static function trackLength(int $track_id): int
	{
		return Distances::pathLength(
			DB::table("locations")
				->select(["longitude", "latitude"])
				->where("track_id", $track_id)
				->orderBy("time")
				->get()
		);
	}
}
