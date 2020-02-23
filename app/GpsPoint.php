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
}
