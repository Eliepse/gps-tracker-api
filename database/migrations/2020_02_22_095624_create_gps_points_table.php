<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGpsPointsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gps_points', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('gps_track_id');
			$table->float('longitude', 12, 8);
			$table->float('latitude', 12, 8);
			$table->float('altitude', 12, 8)->default(0);
			$table->float('accuracy', 6, 2);
			$table->timestamp('time');

			$table->foreign('gps_track_id')
				->references('id')
				->on('gps_tracks')
				->onUpdate('cascade')
				->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('gps_points');
	}
}
