<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAppCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:create {name}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new app';


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
	 * @return void
	 */
	public function handle(): int
	{
		$app = new User();
		$app->name = $this->argument('name');
		$app->uuid = Str::uuid();
		$app->password = Hash::make($password = Str::random(32));
		$app->api_token = Str::random(80);
		$app->save();

		$this->info("App created");
		$this->info("App ID      : " . $app->uuid);
		$this->info("App Password: " . $password);
		$this->info("Token API   : " . $app->api_token);

		return 0;
	}
}
