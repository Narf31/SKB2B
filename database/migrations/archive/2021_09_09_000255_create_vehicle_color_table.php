<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVehicleColorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_color', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->boolean('row')->nullable()->default(0);
			$table->integer('isn')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_color');
	}

}
