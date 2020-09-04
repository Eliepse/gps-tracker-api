<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Console\Commands;

use App\Helpers\Distances;
use App\Track;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OptimizeTracksMetasCommand extends Command
{
	protected $signature = 'tracks:optimize {--force : Recalculate all metas, even if already calculated (optional)}';
	protected $description = 'Calculate all missing tracks\' metas (distance, duration) and stores them in the database.';


	public function __construct()
	{
		parent::__construct();
	}


	public function handle(): int
	{
		foreach (User::all() as $user) {
			$query = $user->tracks();


			if (! $this->option("force")) {
				$query->where(function (Builder $builder) {
					$builder->where("distance", 0)
						->orWhere("duration", 0);
				});
			}

			$this->info("Caching user '{$user->name}' (id: {$user->id})");
			$progress = $this->output->createProgressBar($query->count());

			/** @var Track $track */
			foreach ($query->get() as $track) {
				$locations = DB::table("locations")
					->select(["longitude", "latitude", "time"])
					->where("track_id", $track->id)
					->orderBy("time")
					->get();
				$track->update([
					"distance" => Distances::pathLength($locations),
					"duration" => Carbon::createFromTimeString($locations->last()->time)
						->diffAsCarbonInterval(Carbon::createFromTimeString($locations->first()->time)),
				]);
				$progress->advance();
			}

			$progress->finish();
			$this->line("");
		}

		$this->info(
			"\nIt took "
			. Carbon::createFromTimestamp($_SERVER["REQUEST_TIME"])->diffInMilliseconds()
			. "ms to run this command."
		);

		return 0;
	}
}
