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
 */
class GpsPoint extends Model
{
	protected $table = "gps_points";

	protected $guarded = [];

	public $timestamps = false;

	protected $dates = [
		"time",
	];


	public function track(): BelongsTo
	{
		return $this->belongsTo(GpsTrack::class, "gps_track_id", "id");
	}


	public function distanceTo(GpsPoint $point): float
	{
		$eQuatorialEarthRadius = 6378.1370;
		$d2r = pi() / 180;
		$dlong = ($point->longitude - $this->longitude) * $d2r;
		$dlat = ($point->latitude - $this->latitude) * $d2r;
		$a = pow(sin($dlat / 2.0), 2.0) + cos($this->latitude * $d2r) * cos($point->latitude * $d2r) * pow(sin($dlong / 2.0), 2.0);
		$c = 2.0 * atan2(sqrt($a), sqrt(1.0 - $a));

		return $eQuatorialEarthRadius * $c;
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
