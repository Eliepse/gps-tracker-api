<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('apps', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->uuid('uuid');
			$table->string('password', 32);
			$table->string('api_token', 80)
				->unique()
				->nullable()
				->default(null);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
