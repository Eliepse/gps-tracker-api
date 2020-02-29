<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAltitudeToGpsPointsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gps_points', function (Blueprint $table) {
			$table->double('altitude')
				->after('latitude')
				->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gps_points', function (Blueprint $table) {
			$table->dropColumn("altitude");
		});
	}
}
