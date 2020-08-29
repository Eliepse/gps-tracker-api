<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Console\Commands;

use App\Track;
use App\User;
use Illuminate\Console\Command;

class CacheUsersDistancesCommand extends Command
{
	protected $signature = 'calc-distances {--force : Recalculate all distances, even already calculated (optional)}';
	protected $description = 'Calculate all missing tracks distances and stores them in the database.';


	public function __construct()
	{
		parent::__construct();
	}


	public function handle(): int
	{
		foreach (User::all() as $user) {
			$query = $user->tracks();

			if (! $this->option("force")) {
				$query->select(["id", "user_id"])->where("distance", "===", 0);
			}

			$this->info("Caching user '{$user->name}' (id: {$user->id})");
			$progress = $this->output->createProgressBar($query->count());

			/** @var Track $track */
			foreach ($query->get() as $track) {
				$track->distance = $track->getDistance();
				$track->save();
				$progress->advance();
			}

			$progress->finish();
			$this->line("");
		}

		return 0;
	}
}
