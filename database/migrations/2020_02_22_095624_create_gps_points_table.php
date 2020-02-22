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
			$table->float('longitude', 8, 6);
			$table->float('latitude', 8, 6);
			$table->float('accuracy');
			$table->timestamp('time');

			$table->foreign('gps_track_id')
				->references('id')
				->on('gps_track')
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
