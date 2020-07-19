<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class GpsTrack
 *
 * @package App
 * @property-read int $id
 * @property int app_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read App $app
 * @property-read Collection $points
 */
class GpsTrack extends Model
{
	protected $table = "gps_tracks";

	protected $fillable = ['app_id'];

	private $distance = null;


	public function app(): BelongsTo
	{
		return $this->belongsTo(App::class, 'app_id', 'id');
	}


	public function points(): HasMany
	{
		return $this->hasMany(GpsPoint::class, "gps_track_id", "id");
	}


	public function getDistance(bool $force = false): float
	{
		if (! is_null($this->distance) && ! $force) {
			return $this->distance;
		}

		if ($this->points->count() < 2) {
			return 0.0;
		}

		$this->distance = 0.0;
		for ($h = 0, $i = 1; $i < $this->points->count(); $i++, $h++) {
			$this->distance += $this->points[ $i ]->distanceTo($this->points[ $h ]);
		}
		return $this->distance;
	}


	public function getDuration(): CarbonInterval
	{
		if ($this->points->count() < 2) {
			return CarbonInterval::create(0);
		}

		return $this->points->last()->time->diffAsCarbonInterval($this->points->first()->time);
	}
}
