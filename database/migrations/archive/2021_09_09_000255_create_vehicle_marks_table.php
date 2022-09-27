<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVehicleMarksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_marks', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('title', 256)->nullable();
			$table->integer('category_id')->nullable();
			$table->integer('isn')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_marks');
	}

}
