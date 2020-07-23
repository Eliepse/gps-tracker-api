<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamingFromModelChange extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::rename("apps", "users");
		Schema::rename("gps_tracks", "tracks");
		Schema::rename("gps_points", "locations");

		Schema::table("tracks", function (Blueprint $table) {
			$table->renameColumn("app_id", "user_id");
		});
		Schema::table("locations", function (Blueprint $table) {
			$table->renameColumn("gps_track_id", "track_id");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::rename("users", "apps");
		Schema::rename("tracks", "gps_tracks");
		Schema::rename("locations", "gps_points");
	}
}
