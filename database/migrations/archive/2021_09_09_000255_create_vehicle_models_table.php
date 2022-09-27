<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVehicleModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_models', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 256)->nullable();
			$table->integer('mark_id')->nullable()->default(0);
			$table->integer('category_id')->nullable();
			$table->integer('isn')->nullable();
			$table->integer('rsa_code')->nullable();
			$table->integer('is_risky')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_models');
	}

}
