<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRangeOfGpsFieldsToGpsPoints extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gps_points', function (Blueprint $table) {
			$table->double('longitude')->change();
			$table->double('latitude')->change();
			$table->double('accuracy')->change();
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
			$table->float('longitude', 8, 6)->change();
			$table->float('latitude', 8, 6)->change();
			$table->float('accuracy', 8, 2)->change();
		});
	}
}
