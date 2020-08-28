<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Console\Commands;

use App\Cache\UserDistances;
use App\Track;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CacheUsersDistancesCommand extends Command
{
	protected $signature = 'cache:distances';
	protected $description = 'Caches users total distances.';


	public function __construct()
	{
		parent::__construct();
	}


	public function handle(): void
	{
		$cache = new UserDistances();
		$cache->cached_at = Carbon::now();
		$cache->cached_until = Carbon::now()->subWeeks(3)->startOfWeek();

		foreach (User::all() as $user) {
			$this->info("Caching user '{$user->name}' (id: {$user->id})");
			$progress = $this->output->createProgressBar($user->tracks()->count());
			$total = 0;
			$user->tracks()
				->whereDate("created_at", "<", $cache->cached_until)
				->chunk(100, function (Collection $tracks) use (&$total, $progress) {
					$tracks->load(["locations:id,track_id,longitude,latitude,time"]);
					$total += $tracks->sum(function (Track $track) use ($progress) {
						$progress->advance();
						return $track->getDistance();
					});
				});
			$cache->setUserDustance($user->id, $total);
			$progress->finish();
			$this->line("");
		}

		Cache::forever("users:distances", $cache);
	}
}
