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
		return sqrt(pow($point->latitude - $this->latitude, 2) + pow($point->longitude - $this->longitude, 2));
	}
}
