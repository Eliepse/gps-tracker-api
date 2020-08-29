<?php

namespace App\Casts\Mysql;

use Carbon\CarbonInterval;
use DateInterval;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

final class Time implements CastsAttributes
{

	public function get($model, string $key, $value, array $attributes): CarbonInterval
	{
		return is_string($value) ? CarbonInterval::createFromFormat("H:i:s", $value) : CarbonInterval::seconds(0);
	}


	public function set($model, string $key, $value, array $attributes): string
	{
		if (is_object($value) && is_a($value, DateInterval::class)) {
			return $this->castFromInterval($value);
		}

		if (is_int($value)) {
			return $this->castFromInteger($value);
		}

		$type = is_object($value) ? get_class($value) : gettype($value);
		throw new InvalidArgumentException("The given value is not an instance of DateInterval or an integer, $type given.");
	}


	private function castFromInterval(DateInterval $interval): string
	{
		if (! is_a($interval, CarbonInterval::class)) {
			$interval = CarbonInterval::make($interval);
		}
		$interval->cascade();
		return str_pad(floor($interval->totalHours), 2, "0", STR_PAD_LEFT) . ":" . $interval->format('%I:%S');
	}


	private function castFromInteger(int $duration): string
	{
		return $this->castFromInterval(CarbonInterval::seconds($duration));
	}
}