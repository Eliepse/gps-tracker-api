<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
