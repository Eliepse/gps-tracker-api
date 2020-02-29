<?php

namespace App\Console\Commands;

use App\GpsTrack;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class CleanEmptyCoursesCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:clean';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clean courses that have less than two GpsPoint';


	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		/** @var Collection $tracks */
		$tracks = GpsTrack::select(['id'])
			->withCount("points")
			->get()
			->filter(function ($track) { return $track->points_count < 2; });

		$count = $tracks->count();

		$tracks->each(function (GpsTrack $track) {
			$track->delete();
		});

		$this->info("$count tracks deleted.");
	}
}
