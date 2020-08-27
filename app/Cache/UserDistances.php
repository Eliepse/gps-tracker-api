<?php

namespace App\Cache;

use Carbon\Carbon;
use Illuminate\Support\Collection;

final class UserDistances
{
	public Carbon $cached_at;
	public Carbon $cached_until;
	public Collection $users;


	public function __construct()
	{
		$this->users = collect();
	}


	public function isValid(): bool
	{
		return ! empty($this->cached_at)
			&& $this->users->isNotEmpty();
	}


	public function isUserCached(int $id): bool
	{
		return $this->users->has($id);
	}


	public function setUserDustance(int $id, float $distance): void
	{
		$this->users->put($id, $distance);
	}


	public function getUserDistance(int $id): float
	{
		return $this->users->get($id, 0);
	}
}