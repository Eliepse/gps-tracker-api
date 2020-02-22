<?php

namespace App;

use Carbon\Carbon;
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
 * @property array $nodes
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read App $app
 * @property-read Collection $points
 */
class GpsTrack extends Model
{
	protected $table = "gps_tracks";

	protected $fillable = ['nodes', 'app_id'];

	protected $casts = [
		'nodes' => 'json',
	];


	public function app(): BelongsTo
	{
		return $this->belongsTo(App::class, 'app_id', 'id');
	}


	public function points(): HasMany
	{
		return $this->hasMany(GpsPoint::class, "gps_track_id", "id");
	}
}
