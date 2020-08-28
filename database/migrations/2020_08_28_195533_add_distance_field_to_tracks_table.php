<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistanceFieldToTracksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tracks', function (Blueprint $table) {
			$table->unsignedMediumInteger("distance")
				->after("user_id")
				->default(0)
				->comment("The total distance of this track. For performances, use that value instead of computing from locations.");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tracks', function (Blueprint $table) {
			$table->dropColumn("distance");
		});
	}
}
