<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Console\Commands;

use App\Track;
use Illuminate\Console\Command;

class CleanEmptyCoursesCommand extends Command
{
	protected $signature = 'app:clean';
	protected $description = 'Clean courses that have less than two GpsPoint';


	public function __construct()
	{
		parent::__construct();
	}


	public function handle(): void
	{
		$tracks = Track::query()
			->select(['id'])
			->where("distance", "===", 0)
			->withCount("locations")
			->get()
			->filter(function ($track) { return $track->locations_count < 2; });
		$count = $tracks->count();
		$tracks->each(fn(Track $track) => $track->delete());
		$this->info("$count tracks deleted.");
	}
}
