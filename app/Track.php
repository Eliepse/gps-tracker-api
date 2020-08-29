<?php

namespace App;

use App\Casts\Mysql\Time;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class GpsTrack
 *
 * @package App
 * @property-read int $id
 * @property int $user_id
 * @property int $distance
 * @property CarbonInterval $duration
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read User $user
 * @property-read Collection|Location[] $locations
 */
class Track extends Model
{
	protected $table = "tracks";
	protected $fillable = ['app_id'];
	protected $casts = [
		"duration" => Time::class,
	];

	private $durationWithoutPause = null;


	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}


	public function locations(): HasMany
	{
		return $this->hasMany(Location::class, "track_id", "id")->orderBy("time");
	}


	/**
	 * Calculates the total distance of the track.
	 * Do to its performence cost, the computed data is cached (only on runtime).
	 *
	 * @param bool $force Force to recalculate the distance (do not refresh relations)
	 *
	 * @return int Returns the total distance in meters
	 */
	public function getDistance(bool $force = false): int
	{
		if (is_numeric($this->distance) && $this->distance > 0 && ! $force) {
			return $this->distance;
		}

		if ($this->locations->count() < 2) {
			return 0;
		}

		$this->distance = 0.0;
		for ($h = 0, $i = 1; $i < $this->locations->count(); $i++, $h++) {
			$this->distance += $this->locations[ $i ]->distanceTo($this->locations[ $h ]);
		}
		return $this->distance = round($this->distance);
	}


	/**
	 * Calculates the total duration of the track.
	 *
	 * @param bool $force
	 *
	 * @return CarbonInterval The duration as an interval
	 */
	public function calculateDuration(bool $force = false): CarbonInterval
	{
		if (! $this->duration->isEmpty() && ! $force) {
			return $this->duration;
		}

		if ($this->locations->count() < 2) {
			return CarbonInterval::seconds(0);
		}

		return $this->locations->last()->time->diffAsCarbonInterval($this->locations->first()->time);
	}


	/**
	 * Estimate the duration without the pause, based on a speed threshold between GpsPoint.
	 * Do to its performence cost, the computed data is cached (only on runtime).
	 *
	 * @param bool $force Force to recalculate the estimated duration (do not refresh relations)
	 *
	 * @return CarbonInterval The estimated duration as an interval
	 * @throws Exception
	 */
	public function getEstimatedDurationWithoutPause(bool $force = false): CarbonInterval
	{
		if (! is_null($this->durationWithoutPause) && ! $force) {
			return $this->durationWithoutPause;
		}

		if ($this->locations->count() < 2) {
			return CarbonInterval::create(0);
		}

		$interval = CarbonInterval::create(0);
		for ($h = 0, $i = 1; $i < $this->locations->count(); $i++, $h++) {
			$distance = $this->locations[ $i ]->distanceTo($this->locations[ $h ]);
			$duration = $this->locations[ $h ]->time->diffAsCarbonInterval($this->locations[ $i ]->time);
			if ($distance / $duration->totalSeconds >= config("general.calculations.idle_speed_thershold")) {
				$interval->add($duration);
			}
		}
		return $this->durationWithoutPause = $interval->cascade();
	}
}
