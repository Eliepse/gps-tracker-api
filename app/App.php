<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class App
 *
 * @package App
 * @property-read int $id
 * @property string $name
 * @property string $uuid
 * @property string $password
 * @property string $api_token
 * @property-read Collection $tracks
 */
class App extends Authenticatable
{
	protected $table = 'apps';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'uuid', 'password', 'api_token',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'api_token',
	];


	public function tracks(): HasMany
	{
		return $this->hasMany(GpsTrack::class, 'app_id', 'id');
	}
}
